<?php namespace Vankosoft\ApplicationInstalatorBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Webmozart\Assert\Assert;
use Gedmo\Sluggable\Util\Urlizer;

use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Vankosoft\ApplicationBundle\Model\Interfaces\ApplicationInterface;
use Vankosoft\UsersBundle\Model\UserRoleInterface;

#[AsCommand(
    name: 'vankosoft:application:create',
    description: 'VankoSoft Application Create Command.',
    hidden: false
)]
final class CreateApplicationCommand extends AbstractInstallCommand
{
    const THEME_NONE_ID = 'no-theme';
    
    /** @var ApplicationInterface */
    private $application;
    
    protected function configure(): void
    {
        $this
            ->setHelp(<<<EOT
The <info>%command.name%</info> command allows user to create a VankoSoft Application.
EOT
            )
            ->addArgument( 'applicationType', InputArgument::OPTIONAL, 'The Application Type to be Created.' )
            ->addOption(
                'new-project',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Whether to setup the AdminPanelKernel class.',
                false
            )
            ->addOption(
                'locale',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Prefered User Locale.',
                null
            )
            ->addOption(
                'theme',
                't',
                InputOption::VALUE_OPTIONAL,
                'Default Application Theme.',
                null
            )
        ;
    }
    
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $localeCode         = $this->getApplicationDefaultLocale( $input, $output );
        
        $applicationType    = $input->getArgument( 'applicationType' );
        
        $newProjectOption   = $input->getOption( 'new-project' );
        $newProject         = ( $newProjectOption !== false );
        
        /** @var QuestionHelper $questionHelper */
        $questionHelper     = $this->getHelper( 'question' );
        
        $questionName       = $this->createApplicationNameQuestion();
        $applicationName    = $questionHelper->ask( $input, $output, $questionName );
        
        $appSetup           = $this->get( 'vs_application.installer.setup_application' );
        $outputStyle        = new SymfonyStyle( $input, $output );
        
        $applcationType     = $this->createApplicationTypeQuestion();
        
        // Add Database Records
        $outputStyle->writeln( 'Create Application Database Records.' );
        $this->createApplicationDatabaseRecords( $input, $output, $applicationName, $localeCode );
        $outputStyle->writeln( '<info>Application Database Records successfully created.</info>' );
        $outputStyle->newLine();
        
        // Create Directories
        $outputStyle->writeln( 'Create Application Directories.' );
        $appSetup->setupApplication( $applicationName, $localeCode, $newProject, $applcationType );
        $outputStyle->writeln( '<info>Application Directories successfully created.</info>' );
        $outputStyle->newLine();
        
        // Setup Application Theme
        $outputStyle->newLine();
        $outputStyle->writeln( 'Setup Application Theme.' );
        $theme  = $this->setupApplicationTheme( $input, $output );
        if ( $theme ) {
            $outputStyle->writeln( '<info>Application Theme is setted up.</info>' );
        } else {
            $outputStyle->writeln( '<info>Application Theme is NOT setted up. Set Theme Later.</info>' );
        }
        
        $outputStyle->newLine();
        
        return Command::SUCCESS;
    }
    
    private function getApplicationDefaultLocale( InputInterface $input, OutputInterface $output )
    {
        $defaultLocale  = $input->getOption( 'locale' );
        if ( ! $defaultLocale ) {
            $defaultLocale  = $this->get( 'vs_app.setup.locale' )->setup( $input, $output, $this->getHelper( 'question' ) )->getCode();
        }
        
        return $defaultLocale;
    }
    
    private function createApplicationDatabaseRecords( InputInterface $input, OutputInterface $output, $applicationName, $localeCode )
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper     = $this->getHelper( 'question' );
        
        $outputStyle        = new SymfonyStyle( $input, $output );
        
        /*
         * Create Application
         */
        $this->application  = $this->createApplication( $input, $output, $applicationName );
        
        /*
         * Create Application Base Role
         */
        $baseRole           = $this->createApplicationBaseRole( $input, $output, $applicationName );
        
        /*
         * Create Application Users
         */
        if ( $questionHelper->ask( $input, $output, new ConfirmationQuestion( 'Do you want to create application users? (y/N) ', false ) ) ) {
            $this->createApplicationUsers( $input, $output, $applicationName, $baseRole->getRole(), $localeCode );
        } else {
            $outputStyle->writeln( 'Cancelled creating application users.' );
        }
    }
    
    private function createApplication( InputInterface $input, OutputInterface $output, $applicationName ): ApplicationInterface
    {
        $entityManager      = $this->get( 'doctrine' )->getManager();
        
        /** @var QuestionHelper $questionHelper */
        $questionHelper     = $this->getHelper( 'question' );
        
        $questionUrl        = $this->createApplicationUrlQuestion();
        $applicationUrl     = $questionHelper->ask( $input, $output, $questionUrl );
        $applicationSlug    = $this->get( 'vs_application.slug_generator' )->generate( $applicationName );
        $applicationCreated = new \DateTime;
        
        $application        = $this->get( 'vs_application.factory.application' )->createNew();
        $application->setCode( $applicationSlug );
        $application->setTitle( $applicationName );
        $application->setHostname( $applicationUrl );
        $application->setCreatedAt( $applicationCreated );
        
        $entityManager->persist( $application );
        $entityManager->flush();
        
        return $application;
    }
    
    private function createApplicationBaseRole( InputInterface $input, OutputInterface $output, $applicationName ): UserRoleInterface
    {
        $entityManager      = $this->get( 'doctrine' )->getManager();
        
        /*
         * Create Application Base Role Taxon
         */
        $taxonSlug          = $this->get( 'vs_application.slug_generator' )->generate( 'Role ' . $applicationName );
        $roleTaxon          = $this->get( 'vs_application.factory.taxon' )->createNew();
        $taxonomyRootTaxon  = $this->get( 'vs_application.repository.taxonomy' )
                                                    ->findByCode( 'user-roles' )
                                                    ->getRootTaxon();
        $taxonParent        = $this->get( 'vs_application.repository.taxon' )
                                                    ->findOneBy( ['code' => 'role-application-admin'] );
        
        $roleTaxon->setCurrentLocale( 'en_US' );
        $roleTaxon->setParent( $taxonParent ?: $taxonomyRootTaxon );
        $roleTaxon->setCode( $taxonSlug );
        $roleTaxon->getTranslation()->setName( 'Role ' . $applicationName );
        $roleTaxon->getTranslation()->setDescription( $applicationName );
        $roleTaxon->getTranslation()->setSlug( $taxonSlug );
        $roleTaxon->getTranslation()->setTranslatable( $roleTaxon );
        
        /*
         * Create Application Base Role
         */
        $role               = $this->get( 'vs_users.factory.user_roles' )->createNew();
        $roleParent         = $this->get( 'vs_users.repository.user_roles' )
                                                    ->findByTaxonCode( 'role-application-admin' );
        $role->setParent( $roleParent );
        $role->setTaxon( $roleTaxon );
        
        $adminRole          = 'ROLE_' . \strtoupper( Urlizer::urlize( $applicationName, '_' ) ) . '_ADMIN';
        $role->setRole( $adminRole );
        
        $entityManager->persist( $roleTaxon );
        $entityManager->persist( $role );
        $entityManager->flush();
        
        return $role;
    }
    
    private function createApplicationUsers( InputInterface $input, OutputInterface $output, $applicationName, $baseRole, $localeCode )
    {
        $outputStyle    = new SymfonyStyle( $input, $output );
        $outputStyle->writeln( 'Create Admin account for this Application Only.' );
        
        $parameters     = [
            '--application' => $applicationName,
            '--roles'       => [$baseRole],
            '--locale'      => $localeCode,
            '--designation' => 'Application User',
        ];
        $this->commandExecutor->runCommand( 'vankosoft:application:create-user', $parameters, $output );
        
        $outputStyle->writeln( '<info>Admin account for this Application successfully created.</info>' );
        $outputStyle->newLine();
    }
    
    private function setupApplicationTheme( InputInterface $input, OutputInterface $output ): ?ThemeInterface
    {
        $entityManager          = $this->get( 'doctrine' )->getManager();
        $settingsRepository     = $this->get( 'vs_application.repository.settings' );
        
        $settings   = $settingsRepository->getSettings( $this->application );
        if ( ! $settings ) {
            $settings   = $this->get( 'vs_application.factory.settings' )->createNew();
        }
        
        $settings->setApplication( $this->application );
        $settings->setMaintenanceMode( 0 );
        
        $applicationThemeName   = $this->createApplicationThemeQuestion( $input, $output );
        $theme                  = null;
        if ( $applicationThemeName && $applicationThemeName !== self::THEME_NONE_ID ) {
            $theme                  = $this->get( 'vs_app.theme_repository' )->findOneByName( $applicationThemeName );
            if ( $theme ) {
                $settings->setTheme( $applicationThemeName );
            }
        }
        
        $entityManager->persist( $settings );
        $entityManager->flush();
        
        return $theme;
    }
    
    private function createApplicationNameQuestion(): Question
    {
        return ( new Question( 'Application Name: ' ) )
            ->setValidator(
                function ( $value ): string {
                    /** @var ConstraintViolationListInterface $errors */
                    $errors = $this->get( 'validator' )->validate( (string) $value, [new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Your application name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your application name cannot be longer than {{ limit }} characters',
                    ])]);
                    foreach ( $errors as $error ) {
                        throw new \DomainException( $error->getMessage() );
                    }
                    
                    return $value;
                }
            )
            ->setMaxAttempts( 3 )
        ;
    }
    
    private function createApplicationUrlQuestion(): Question
    {
        return ( new Question( 'Application Domain: ' ) )
            ->setValidator(
                function ( $value ): string {
                    /** @var ConstraintViolationListInterface $errors */
                    $errors = $this->get( 'validator' )->validate( (string) $value, [new Length([
                        'min' => 6,
                        'max' => 256,
                        'minMessage' => 'Your application url must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your application url cannot be longer than {{ limit }} characters',
                    ])]);
                    foreach ( $errors as $error ) {
                        throw new \DomainException( $error->getMessage() );
                    }
                    
                    return $value;
                }
            )
            ->setMaxAttempts( 3 )
        ;
    }
    
    private function createApplicationThemeQuestion( InputInterface $input, OutputInterface $output ): ?string
    {
        $availableThemes        = array_keys( $this->get( 'vs_app.theme_repository' )->findAll() );
        \array_unshift( $availableThemes , self::THEME_NONE_ID );
        
        $applicationThemeName   = null;
        if ( ! empty( $availableThemes ) ) {
            $defaultTheme       = $input->getOption( 'theme' );
            $default            = null;
            if ( $defaultTheme ) {
                $questionMessage    = sprintf( 'Please select an application theme to use (defaults to %s): ', $defaultTheme );
                $default            = $defaultTheme;
            } else {
                $questionMessage    = sprintf( 'Please select an application theme to use (defaults to %s): ', $availableThemes[0] );
                $default            = $availableThemes[0];
            }
            
            $choiceQuestion = new ChoiceQuestion(
                $questionMessage,
                // choices can also be PHP objects that implement __toString() method
                $availableThemes,
                $default
            );
            $choiceQuestion->setErrorMessage( 'Theme %s is invalid.' );
            
            $applicationThemeName   = $this->getHelper( 'question' )->ask(
                $input,
                $output,
                $choiceQuestion
            );
        }
        
        return $applicationThemeName;
    }
    
    private function createApplicationTypeQuestion( InputInterface $input, OutputInterface $output ): ?string
    {
        $applicationTypes   = [
            self::APPLICATION_TYPE_STANDRD,
            self::APPLICATION_TYPE_CATALOG,
            self::APPLICATION_TYPE_EXTENDED,
        ];
        
        $default            = $applicationTypes[2];
        $questionMessage    = sprintf( 'Please select an application type to be created (defaults to %s): ', $default );
        
        $choiceQuestion     = new ChoiceQuestion(
            $questionMessage,
            // choices can also be PHP objects that implement __toString() method
            $applicationTypes,
            $default
        );
        
        $applicationType    = $this->getHelper( 'question' )->ask(
            $input,
            $output,
            $choiceQuestion
        );
        
        return $applicationType;
    }
}
