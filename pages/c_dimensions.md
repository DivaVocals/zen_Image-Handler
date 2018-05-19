[Back](configuration.md "Return to the Configuration page")
# Image Dimensions

There are different approaches to setting the dimension of images.

1. You can do it the "normal" way and simply specify width and height dimensions in pixels &mdash; setting width to 100 and height to 80, for example. If your image is 200x200 it will be resized to 80x80 because this is the biggest size that fits into 100x80.

2. You can set the width (or height) to a specific value, e.g. 100, and leave the other dimension blank (or set to 0). The height (or width) will be calculated according to the correct aspect ratio.

3. If you append an exclamation-point (`!`) to one value (e.g. `80!`), the generated re-sized images are centered on a canvas that matches **exactly** the given size for the corresponding image. If you specified `100x80!`, a 200x200 image will be resized to `80x80` and placed centered on a `100x80` canvas filled with the specified background color. Think of it as kind of forcing image dimensions without messing up the aspect ratio.

If you are unsure how to set the height and width of your images, a suggested approach is to only set the `width` settings and leave the associated `height` blank. An image's height will be correctly calculating (based on the width setting) so long as you have set:

1. _Calculate Image Size_ to *Yes*
2. _Image - Use Proportional Images on Products and Categories_ to *1*

The reasoning here is that most shop owners (not all) will not go through the trouble and effort to make all of their product images the _same size_, nor will they have the ability to calculate the correct proportional sizes for their small and medium images (relative to the large image size). So unless you are willing to take the time to calculate what your small and medium image proportions should be relative to the large product image, it's best to set the width of the small, medium and large images, leave the height blank and let IH<sup>5</sup> and Zen Cart do the heavy lifting for you to automatically calculate the correct height.

## Image Size Options (Configuration)

A huge source for confusion is the many image size options in the admin's **Configuration->Images**. Here are some of the image-size options on that page and where they are used:

| Configuration Setting | Where Used |
| ------- | ------- |
| Small Image | Product images that appear in the sideboxes (New Products, Featured Products, etc) or are additional product images on a product details page (e.g. `product_info`, `product_music_info`). |
| Image - Product Listing | Product images that appear in the product category listing pages. |
| Image - Product New Listing | Product images that appear on the new-products' listing page (`products_new`). |
| Image - Featured Products | Product images that appear on the featured-products' listing page (`featured_products`). |
| Image - Product All Listing | Product images that appear on the all-products' listing page (`products_all`). |
| Product Info | The main product-image that appears on a product-details page &mdash. a.k.a. the `medium` image. |
| IH small image | Product images that appear in the sideboxes and all the product-listing images. |
| IH medium image | Product image that appears in a product's details page and the rollover image displayed when hovering your mouse over the the small images. |
| IH large image | The large product image, the one that displays when you click on the "larger image" link on a product's details page. |