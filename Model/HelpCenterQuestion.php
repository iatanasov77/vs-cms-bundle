<?php namespace Vankosoft\CmsBundle\Model;

use Vankosoft\CmsBundle\Model\Interfaces\HelpCenterQuestionInterface;
use Vankosoft\ApplicationBundle\Model\Traits\TranslatableTrait;

class HelpCenterQuestion implements HelpCenterQuestionInterface
{
    use TranslatableTrait;
    
    /** @var integer */
    protected $id;
    
    /** @var string */
    protected $question;
    
    /** @var string */
    protected $answer;
    
    public function __construct()
    {
        $this->fallbackLocale   = 'en_US';
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getQuestion(): ?string
    {
        return $this->question;
    }
    
    public function setQuestion($question)
    {
        $this->question   = $question;
        
        return $this;
    }
    
    public function getAnswer(): ?string
    {
        return $this->answer;
    }
    
    public function setAnswer($answer)
    {
        $this->answer  = $answer;
        
        return $this;
    }
}