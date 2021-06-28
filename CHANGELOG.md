0.6.10	|	Release date: **28.06.2021**
============================================
* Bug-Fixes:
  - Fix Translations.


0.6.9	|	Release date: **28.06.2021**
============================================
* Bug-Fixes:
  - Fix Templates Namespace.


0.6.8	|	Release date: **28.06.2021**
============================================
* New Features:
  - Add PageCategoriesRepository Method to Find Vategory by Taxon Code.


0.6.7	|	Release date: **26.06.2021**
============================================
* New Features:
  - Add Template's Translations.


0.6.6	|	Release date: **22.06.2021**
============================================
* New Features:
  - Symfony 5 autowiring for Controllers.


0.6.5	|	Release date: **18.06.2021**
============================================
* Bug-Fixes:
  - Add Controller Services.
  - Fix TwigTemplates.


0.6.4	|	Release date: **18.06.2021**
============================================
* Bug-Fixes and Improvements:
  - Fix TwigTemplate.
  - Update VankosoftApplicationBundle version.


0.6.3	|	Release date: **17.06.2021**
============================================
* Bug-Fixes:
  - Fix Controlers to get Pages Category Taxonomy ID by Taxonomy Code.


0.6.2	|	Release date: **14.06.2021**
============================================
* New Features:
  - Upgrade application package to 0.10 version.


0.6.1	|	Release date: **11.06.2021**
============================================
* New Features:
  - Fix Symfony5 issues.


0.6.0	|	Release date: **11.06.2021**
============================================
* New Features:
  - Add Composer Dependencies.


0.5.8	|	Release date: **29.05.2021**
============================================
* Many Fixes and Improvements:
  - Fixes of Forms, Controller and Twig Templates. 
  - Add PreviewPageForm.


0.5.7	|	Release date: **17.05.2021**
============================================
* New Features:
  - Add Route and ControllerAction for Pages Preview.
  - Make PagesController to sent available translations and versions to the template.


0.5.6	|	Release date: **17.05.2021**
============================================
* New Features:
  - Add route to get page form in different locales.
  - Make Pages Translatable not only in default locale.
  - Make Page slugs lowercases and use dash for slug separator.
  - Update Page Model to use Versioning of Body Field.


0.5.5	|	Release date: **07.05.2021**
============================================
* Bug-Fixes:
  - Fix Model Classes and Interfaces return types.


0.5.4	|	Release date: **25.04.2021**
============================================
* Bug-Fixes:
  - Fix clonePage action.


0.5.3	|	Release date: **24.04.2021**
============================================
* New Features:
  - Add Fool CkEditor Toolbar into Page::text field.
  - Separate routes to Show Page by ID and by SLUG.


0.5.2	|	Release date: **24.04.2021**
============================================
* Bug-Fixes:
  - Fix Form Labels Translations. Adding translation_domain to forms.


0.5.1	|	Release date: **23.04.2021**
============================================
* New Features:
  - Add Form Lable Trabslations.
  - Add isPublished method of Page Entity.
* Bug-Fixes:
  - Fix! Enable to save uncategorized pages.


0.5.0	|	Release date: **22.04.2021**
============================================
* New Features:
  - Add Page Clone Action.
  - Add Move Functionality of Pages Categories Tree Table.
* Improvements:
  - Remove setTranslatableLocale in saving PageCategory.


0.4.1	|	Release date: **20.04.2021**
============================================
* Bug-Fixes:
  - Fix Category Tree with Pages for EasyUi Combo.


0.4.0	|	Release date: **20.04.2021**
============================================
* New Features:
  - Create Custom Controller Action to get Category Tree with Pages for EasyUi Combo.


0.3.9	|	Release date: **20.04.2021**
============================================
* Bug-Fixes:
  - Remove PageCategoryRelation from models.


0.3.8	|	Release date: **20.04.2021**
============================================
* Bug-Fixes:
  - Fix Page many-to-many mapping.
  - Fix Page::removeCategory


0.3.7	|	Release date: **19.04.2021**
============================================
* New Features:
  - Remove All Pages Categories if not selected by form.


0.3.6	|	Release date: **19.04.2021**
============================================
* Bug-Fixes:
  - Fix Route that get EasyUI Combotree with selected categories.


0.3.5	|	Release date: **19.04.2021**
============================================
* New Features:
  - Add Route to get EasyUI Combotree with selected categories.


0.3.4	|	Release date: **19.04.2021**
============================================:
* Bug-Fixes:
  - Fix PageController to get values of unmapped 'category_taxon' field.


0.3.3	|	Release date: **19.04.2021**
============================================
* Bug-Fixes:
  - Fix Page Form 'category_taxon' field to not validate when use EasyUiCombo.


0.3.2	|	Release date: **19.04.2021**
============================================
* Bug-Fixes:
  - Fix Pages Categories many2many relation.
  - Pass taxonomyId for pages categories into PagesController templates.


0.3.1	|	Release date: **18.04.2021**
============================================
* New Features:
  - Add Custom route that can update PageCategory name by TaxonId.


0.3.0	|	Release date: **18.04.2021**
============================================
* New Features:
  - Add Custom route that can delete PageCategory by TaxonId.


0.2.3	|	Release date: **17.04.2021**
============================================
* Bug-Fixes:
  - Fix Page Categories Callbacks


0.2.2	|	Release date: **15.04.2021**
============================================
* New Features:
  - Add GetImageController.


0.2.1	|	Release date: **15.04.2021**
============================================
* Dependencies:
  - Add LiipImagineBundle as composer requirements.


0.2.0	|	Release date: **07.03.2021**
============================================
* Iprovements, Refactoring and Fixes:
  - Update composer.json
  - Fix PageCategory create with parent
  - Fix PageCategoryForm to use method PUT for update action.
  - Fix PageCategoryForm to show/edit parent combo.
  - Create PageCategoryRelation model.
  - Fix PagesExtController to show category tree from taxonomy.
  - Fix template includes namespaces.
  - Fix to changes of AbstractCrudController.
  - Fix of PagesCategoryController to use taxonomy.
  - Pages Controller to extend VsApplication AbstractCrudController.


0.1.0	|	Release date: **20.01.2021**
============================================
* First Release.


