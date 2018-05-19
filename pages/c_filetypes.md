[Back](configuration.md "Return to the Configuration page")
# Preferred Filetypes
You can select either **.png**, **.jpg**, **.gif** or **no_change** for every image size. The original images you upload are kept as they are, but the IH<sup>5</sup>-generated files (the ones stored in the `bmz_cache` directories) will be created using the `filetype` you specify in these settings.

Let's say that you want the _large_ and _medium_ images to be .png files:

1.     Choose **.png** for your IH medium images filetype and IH large images filetype settings.
1.     Leave the small image at the default setting of no_change.
1.     Upload a **.jpg** file to the product you are updating.

IH<sup>5</sup> will

1.     Generate **.png** image files for your _large_ and _medium_ product images regardless the format of the original image (in this example a **.jpg**).
1.     Since `no_change` was selected for your small images, the small product image will be a resized **.jpg**.

The filetype feature provides another means to let IH<sup>5</sup> do some of the heavy lifting for you. Instead of having to convert your 1000 .jpg product images to .png's, you can let IH<sup>5</sup> do this work for you.

Zen Cart will only recognize and display product images that have the exact same file format for each image size. In other words, all small images must be the same filetype, all medium images must be the same filetype, and all large images must be the same filetype.

... But ...

The various images' sizes you can have a "mixed bag". For example, you might decide that all small images will be gif's, all medium images will be jpg's, and all large images will be png's. The filetype feature allows you to do just that.

**Caution:** IH<sup>5</sup> will allow you to add any valid image format as an additional image to a product — even if the additional image being uploaded is in a different file format than the main product image (e.g. the main image is a jpg and the additional images are png). IH<sup>5</sup> will display mismatched file extensions in <span style="color: red;">red</span> in the additional images table. This visual indication tells you that you've uploaded an additional image with a different filetype than the main product image. Zen Cart will not "see" this additional image because the file format of the new image does not match that of the main product image.

**Notes:**

1.     GIF is good for small thumbnails and features some basic transparency.
1.     JPG doesn't feature transparency, but has a very good file quality/compression ratio, especially for photographic images. This would be your desired filetype for large and possibly medium sized images.
1.     PNG files feature alpha-transparency and as many colors as jpg but are generally larger in filesize than both jpg and gif. Older versions of Internet Explorer had issues displaying alpha-transparency, so you better stick with gif, if you need transparency and support those older IE versions.
