# Image Handler v5.1.6

The latest released version is available for download from the Zen Cart site, via [this](https://www.zen-cart.com/downloads.php?do=file&id=2169) link.  If you need basic installation help, and neither this information nor the included readme does not help you, please visit the [Image Handler v5.x Support Thread](https://www.zen-cart.com/showthread.php?222983)

## Purpose and Aim

Image Handler 5 simplifies the management of product images (particularly the management of additional product images), and to help improve page performance by optimizing those images. Product images (based on your image settings) are created in the Image Handler<sup>5</sup> `bmz_cache` directory. The original images are **not** modified, so it's _perfectly_ safe to use on an existing store.

Image Handler 5 enables you to use GD libraries or ImageMagick (if installed on your server) to generate and resize small, medium and large images on the fly  as they're required. You can simply upload just one image or you can have different sources for medium and large images. Image Handler 5 further enables you to watermark your images (overlay a second specific translucent image) and have medium or large images pop up when you move your mouse over a small image (fancy hover).

This contribution includes a powerful admin interface to browse your products just like you would with the Attribute Manager and upload, delete or add additional images without having to do this manually via FTP. IH<sup>5</sup> works fine with mass update utilities like EZ-Populate and Database I/O Manager.

### Features
* Improve site performance (faster loading, faster display)
* Professional looking images (no stair-effects, smooth edges)
* Choose preferred image-types for each image size
* Uploading one image automatically creates small, medium and large images on page request
* Drops in and out seamlessly. No need to redo your images. All images are kept.
* Easy install. Built in auto-installer creates the database elements required.
* Works with mass-update/-upload tools like EZ-Populate or Database I/O Manager.
* Watermark images to prevent competitors from stealing them.
* Fancy image hover functionality lets a larger image pop up whenever you move your mouse above a small image (switchable).
* Choose an image background color matching to match you site's color or select a transparent background for your images.
* Manage your multiple images for products easily from one page just like you do with attributes in the Products Attribute Manager.

IH<sup>5</sup> works _with_ default Zen Cart functionality, it does not replace it. There are a couple of threads which explain what Image Handler *is* and more importantly what it *is not*. It is suggested that you read everything before you install IH<sup>5</sup>. Here are some additional FAQs which discuss how product images and the override system work in Zen Cart. If you are new to Zen Cart and web development, you should read these threads <b>before</b> you install Image Handler<sup>5</sup>.

- https://www.zen-cart.com/showpost.php?p=978439&postcount=6205
- https://www.zen-cart.com/showpost.php?p=989297&postcount=6360 

Additional information is available by clicking the following links:

### [Configuration](pages/configuration.md)
### [Admin: Image Handler](pages/image_handler.md)
### [Troubleshooting](pages/troubleshooting.md)
