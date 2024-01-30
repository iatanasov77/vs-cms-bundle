const Encore        = require( '@symfony/webpack-encore' );
const pathExists    = require( 'path-exists' );

const projectAssetsPath             = './assets';
const applicationAssetsPath         = './vendor/vankosoft/application/src/Vankosoft/ApplicationBundle/Resources/themes/default/assets';
const usersSubscriptionsAssetsPath  = './vendor/vankosoft/users-subscriptions-bundle/lib/Resources/themes/default/assets';
const paymentAssetsPath             = './vendor/vankosoft/payment-bundle/lib/Resources/themes/default/assets';
const catalogAssetsPath             = './vendor/vankosoft/catalog-bundle/lib/Resources/themes/default/assets';

Encore
    .setOutputPath( 'public/admin-panel/build/default/' )
    .setPublicPath( '/build/default/' )
  
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: true
    })
    
    /**
     * Add Entries
     */
     .autoProvidejQuery()
     .configureFilenames({
        js: '[name].js?[contenthash]',
        css: '[name].css?[contenthash]',
        assets: '[name].[ext]?[hash:8]'
    })
    
    // FOS CkEditor
    .copyFiles([
        {from: './node_modules/ckeditor4/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        
        // Add This When Debugging With Dev Package: https://github.com/ckeditor/ckeditor4.git
        // {from: './node_modules/ckeditor4/core', to: 'ckeditor/core/[path][name].[ext]'},
        
        {from: './node_modules/ckeditor4/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor4/skins', to: 'ckeditor/skins/[path][name].[ext]'}
    ])
    
    // CKeditor 4 Extra Plugins
    .copyFiles([
        {from: applicationAssetsPath + '/vendor/ckeditor4_plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
    ])
    
    .copyFiles([
        {from: applicationAssetsPath + '/images', to: 'images/[path][name].[ext]'},
        {from: './node_modules/bootstrap-sass/assets/fonts/bootstrap', to: 'fonts/bootstrap/[name].[ext]'},
    ])
     
    //////////////////////////////////////////////////////////////////
    // ASSETS
    //////////////////////////////////////////////////////////////////
    .addEntry( 'js/app', applicationAssetsPath + '/js/app.js' )
    .addStyleEntry( 'css/global', applicationAssetsPath + '/css/main.scss' )
    
    .addEntry( 'js/resource-delete', applicationAssetsPath + '/js/pages/resource-delete.js' )
    
    .addEntry( 'js/settings', applicationAssetsPath + '/js/pages/settings.js' )
    .addEntry( 'js/applications', applicationAssetsPath + '/js/pages/applications.js' )
    .addEntry( 'js/profile', applicationAssetsPath + '/js/pages/profile.js' )
    .addEntry( 'js/taxonomy-vocabolaries', applicationAssetsPath + '/js/pages/taxonomy-vocabolaries.js' )
    .addEntry( 'js/taxonomy-vocabolaries-edit', applicationAssetsPath + '/js/pages/taxonomy-vocabolaries-edit.js' )
    .addEntry( 'js/locales', applicationAssetsPath + '/js/pages/locales.js' )
    .addEntry( 'js/cookie-consent-translations', applicationAssetsPath + '/js/pages/cookie-consent-translations.js' )
    .addEntry( 'js/cookie-consent-translations-edit', applicationAssetsPath + '/js/pages/cookie-consent-translations-edit.js' )
    .addEntry( 'js/tags-whitelist-contexts', applicationAssetsPath + '/js/pages/tags-whitelist-contexts.js' )
    
    .addEntry( 'js/pages-categories', applicationAssetsPath + '/js/pages/pages_categories.js' )
    .addEntry( 'js/pages-categories-edit', applicationAssetsPath + '/js/pages/pages_categories_edit.js' )
    .addEntry( 'js/pages-index', applicationAssetsPath + '/js/pages/pages-index.js' )
    .addEntry( 'js/pages-edit', applicationAssetsPath + '/js/pages/pages-edit.js' )
    .addEntry( 'js/documents-index', applicationAssetsPath + '/js/pages/documents-index.js' )
    .addEntry( 'js/documents-edit', applicationAssetsPath + '/js/pages/documents-edit.js' )
    .addEntry( 'js/toc-pages', applicationAssetsPath + '/js/pages/toc-pages.js' )
    .addEntry( 'js/toc-pages-delete', applicationAssetsPath + '/js/pages/toc-pages-delete.js' )
    
    .addEntry( 'js/users-index', applicationAssetsPath + '/js/pages/users-index.js' )
    .addEntry( 'js/users-edit', applicationAssetsPath + '/js/pages/users-edit.js' )
    .addEntry( 'js/users-roles-index', applicationAssetsPath + '/js/pages/users-roles-index.js' )
    .addEntry( 'js/users-roles-edit', applicationAssetsPath + '/js/pages/users-roles-edit.js' )
    
    .addEntry( 'js/filemanager-index', applicationAssetsPath + '/js/pages/filemanager-index.js' )
    .addEntry( 'js/filemanager-file-upload', applicationAssetsPath + '/js/pages/filemanager-file-upload.js' )
    
    .addEntry( 'js/widget-groups', applicationAssetsPath + '/js/pages/widget-groups.js' )
    .addEntry( 'js/widgets', applicationAssetsPath + '/js/pages/widgets.js' )
    
    //////////////////////////////////////////////////////////////////
    // Payment Pages
    //////////////////////////////////////////////////////////////////
    .addEntry( 'js/gateway-config', applicationAssetsPath + '/js/payment_pages/gateway-config.js' )
    .addEntry( 'js/currencies', applicationAssetsPath + '/js/payment_pages/currencies.js' )
    .addEntry( 'js/exchange-rates', applicationAssetsPath + '/js/payment_pages/exchange-rates.js' )
    .addEntry( 'js/product-categories', applicationAssetsPath + '/js/payment_pages/product-categories.js' )
    .addEntry( 'js/product-categories-edit', applicationAssetsPath + '/js/payment_pages/product-categories-edit.js' )
    .addEntry( 'js/products-index', applicationAssetsPath + '/js/payment_pages/products-index.js' )
    .addEntry( 'js/products-edit', applicationAssetsPath + '/js/payment_pages/products-edit.js' )
    .addEntry( 'js/pricing-plan-categories', applicationAssetsPath + '/js/payment_pages/pricing-plan-categories.js' )
    .addEntry( 'js/pricing-plan-categories-edit', applicationAssetsPath + '/js/payment_pages/pricing-plan-categories-edit.js' )
    .addEntry( 'js/pricing-plans-index', applicationAssetsPath + '/js/payment_pages/pricing-plans-index.js' )
    .addEntry( 'js/pricing-plans-edit', applicationAssetsPath + '/js/payment_pages/pricing-plans-edit.js' )
    .addEntry( 'js/recieved-payments', applicationAssetsPath + '/js/payment_pages/recieved-payments.js' )
    .addEntry( 'js/orders', applicationAssetsPath + '/js/payment_pages/orders.js' )
    .addEntry( 'js/pricing-plan-subscriptions', applicationAssetsPath + '/js/payment_pages/pricing-plan-subscriptions.js' )
    .addEntry( 'js/pricing-plan-subscription-payments', applicationAssetsPath + '/js/payment_pages/pricing-plan-subscription-payments.js' )
    .addEntry( 'js/stripe-webhook-endpoint', applicationAssetsPath + '/js/payment_pages/stripe-webhook-endpoint.js' )
    .addEntry( 'js/coupon-objects', applicationAssetsPath + '/js/payment_pages/coupon-objects.js' )
    .addEntry( 'js/coupons-index', applicationAssetsPath + '/js/payment_pages/coupons-index.js' )
    .addEntry( 'js/coupons-edit', applicationAssetsPath + '/js/payment_pages/coupons-edit.js' )
    
    //////////////////////////////////////////////////////////////////
    // Subscription Pages
    //////////////////////////////////////////////////////////////////
    .addEntry( 'js/payed-services-edit', applicationAssetsPath + '/js/subscription_pages/payed-services-edit.js' )
    .addEntry( 'js/payed-services-listing', applicationAssetsPath + '/js/subscription_pages/payed-services-listing.js' )
    .addEntry( 'js/payed-services-categories-listing', applicationAssetsPath + '/js/subscription_pages/payed-services-categories-listing.js' )
    .addEntry( 'js/mailchimp-audiences-listing', applicationAssetsPath + '/js/subscription_pages/mailchimp-audiences-listing.js' )
    .addEntry( 'js/payed-service-subscriptions', applicationAssetsPath + '/js/subscription_pages/payed-service-subscriptions.js' )
;

//////////////////////////////////////////////////////////////////
// Subscription Pages
//////////////////////////////////////////////////////////////////
if ( pathExists.sync( usersSubscriptionsAssetsPath ) ) {
    Encore
        .addEntry( 'js/payed-services-edit', usersSubscriptionsAssetsPath + '/js/pages/payed-services-edit.js' )
        .addEntry( 'js/payed-services-listing', usersSubscriptionsAssetsPath + '/js/pages/payed-services-listing.js' )
        .addEntry( 'js/payed-services-categories-listing', usersSubscriptionsAssetsPath + '/js/pages/payed-services-categories-listing.js' )
        .addEntry( 'js/mailchimp-audiences-listing', usersSubscriptionsAssetsPath + '/js/pages/mailchimp-audiences-listing.js' )
        .addEntry( 'js/payed-service-subscriptions', usersSubscriptionsAssetsPath + '/js/pages/payed-service-subscriptions.js' )
    ;
}

const config = Encore.getWebpackConfig();
config.name = 'adminPanel';

module.exports = config;
