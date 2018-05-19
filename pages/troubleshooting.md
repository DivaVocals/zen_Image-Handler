[Back](../README.md "Return to the main page")
# Troubleshooting

## Basics

Make sure your custom template is active &mdash; **Tools->Template Selection**.

Make sure Image Handler<sup>5</sup> is installed. Set permissions in both your `images` and `bmz_cache` folders to 755 or 777, depending on what your webhost allows; both of these folders need to have the same permissions.

If IH<sup>5</sup> does not work or gives you errors:

- Make sure all files are in correct location
- Make sure you uploaded ALL the Image Handler5 files
- Make sure the files are not corrupt from bad FTP transfers
- Make sure your file merge edits are correct
- Make sure you re-read the [Configuration](configuration.md) and [Admin: Image Handler](image_handler.md) sections!!!
- Make sure that there are no javascript conflicts
- Make sure that your main product image files names DO NOT contain any special characters (non-alphanumeric characters such as / \ : ! @ # $ % ^ < > , [ ] { } & * ( ) + = ). Always use proper file-naming practices when naming your images.

## Activate the IH<sup>5</sup> Logging

Starting with IH<sup>5</sup> v5.0.1, a "trace" capability is available. To enable that feature, you will edit `/includes/extra_datafiles/image_handler_logging.php`:

```php
<?php
// -----
// Part of the Image Handler-5 plugin, v5.0.1 and later.  Provided by Cindy Merkin (lat9)
// Copyright (C) 2018, Vinos de Frutas Tropicales.
//
if (!defined('IH_DEBUG_ADMIN')) {
    define('IH_DEBUG_ADMIN', 'false');
}
if (!defined('IH_DEBUG_STOREFRONT')) {
    define('IH_DEBUG_STOREFRONT', 'false');
}
```

You can separately enable the debug-logging for the admin and the storefront by setting the respective definition to `'true'`.  When its debug is enabled, IH<sup>5</sup> creates a page-access based log file identifying the processing that it has performed on the page.  The information is logged to `/logs/ihdebugYYYYMMDD-HHMMSS-mmmmmm.log` in a format similar to:

```

2018-05-19 11:48:11: (/zc156/index.php?main_page=index&cPath=1_9) __constructor for C:/xampp/htdocs/zc156/images/dvd/theres_something_about_mary.gif.
		calculate_size(100, 80), getimagesize returned 100 x 80.
		resizing is allowed.
		get_resized_image(100, 80, , )
		calculate_size(100, 80), getimagesize returned 100 x 80.
		... returning images/dvd/theres_something_about_mary.gif

2018-05-19 11:48:11: __constructor for C:/xampp/htdocs/zc156/images/medium/dvd/theres_something_about_mary_MED.gif.
		calculate_size(150, 120), getimagesize returned 150 x 120.
		resizing is allowed.
		get_resized_image(150, 120, , )
		calculate_size(150, 120), getimagesize returned 150 x 120.
		... returning images/medium/dvd/theres_something_about_mary_MED.gif

```

## Prepare Your Site for Growth

Not many users are aware that IH<sup>5</sup> can manage the needs of a very large site as easily as it does a small one. When first building a site, the owner of a small site needs only to load images to the images folder. But when the site gets bigger and images multiply like rabbits, this can cause file naming confusions for Zen Cart and slow down the site. Preparing for your business to grow from the beginning will save you hours of work later on!

Without IH<sup>5</sup> installed, Zen Cart requires you to create, optimize, and upload three different size images for each image you want to use. You must name these images using naming suffixes, and place them in corresponding folders inside your main image folder. For example: A product called "Widget" requires images/widget.jpg (small image) images/medium/widget_MED.jpg (medium image) and images/large/widget_LRG.jpg. This is such a hassle, especially if many of your products have multiple images. And as your site grows, it becomes an impossible task!

With IH<sup>5</sup>, you no longer have to make three sizes of the same images and place them in different folders (unless you want to)! Instead, you need upload only one image in one folder and Image Handler5 will do the rest! Simply upload your largest highest quality image and Image Handler5 will resize and optimize your image as needed, and serve up small, medium, or large image sizes appropriate to the page loaded - all automatically and all without actually modifying your original image file in any way! Check out the [Configuration](configuration.md) section of this readme for more info about this awesome functionality!

Prepare your site for growth by simply creating sub-directories in your main `images` directory. For example, you may want to put all your "widget" images in a sub-directory named `widgets` and all your doodad images in a sub-directory named `doodads` , like this:

### Product: Blue Widget with 3 images

`/images/widgets/blue_widget1.jpg` (main product image for a blue widget, e.g. front view)
`/images/widgets/blue_widget1_1.jpg` (additional product image for a blue widget, e.g. side view)
`/images/widgets/blue_widget1_2.jpg` (additional product image for a blue widget, e.g. rear view)

### Product: Red Widget with 1 image

`/images/widgets/red_widget.jpg` (main product image for a red widget)

### Product: Gold Doodad with 2 images

`/images/doodads/gold_doodad1.jpg` (main product image for a gold doodad, e.g. view from above)
`/images/doodads/gold_doodad1_1.jpg` (additional product image for a gold doodad, e.g. view from side)

### Product: Silver Doodad with 3 images

`/images/doodads/silver_doodad1.jpg` (main product image for a silver doodad, e.g. product)
`/images/doodads/silver_doodad1_1.jpg` (additional product image for a silver doodad, e.g. product detail)
`/images/doodads/silver_doodad1_3.jpg` (additional product image for a silver doodad, e.g. product's silver stamp)

--------------

Using Image Handler<sup>5</sup>, you can easily sort and manage thousands of images without confusion or hassle! When selecting the main image for a product in the IH<sup>5</sup> interface, you pick the location for this image. This prompt disappears afterwards because the additional images need to be in the same directory as their main product image and IH<sup>5</sup> handles that automatically!

## Zen Cart Image-Related FAQs

Image Handler<sup>5</sup> reduces the effort required to setup images for your store. It works **with** default Zen Cart functionality, it does not *replace* it. Here are some additional FAQs which discuss how product images work in Zen Cart.

1. [Image Preparation - How-to](https://www.zen-cart.com/content.php?223-image-preparation-how-to)
1. [My images are distorted/fuzzy/squished, help?](https://www.zen-cart.com/content.php?72-my-images-are-distorted-fuzzy-squished)

Information on how Zen Cart identifies/manages additional product images can be found on these Zen Cart FAQs:

1. [Why am I seeing images for other products on my product pages?](https://www.zen-cart.com/content.php?273-why-am-i-seeing-images-for-other-products-on-my-product-pages)
1. [How do I add multiple images to a product?](https://www.zen-cart.com/content.php?100-how-do-i-add-multiple-images-to-a-product)
1. [How do I add more than one image of a product?](https://www.zen-cart.com/content.php?211-how-do-i-add-more-than-one-image-of-a-product) I want to have a main image and also one or two other images that show more parts of a particular product. How/where do I add additional images to a product page? Thanks!

Check out these FAQs and see if they help clarify how Zen Cart works with product images.
