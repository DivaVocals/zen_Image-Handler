[Back](configuration.md "Return to the Configuration page")
# Zooming (fancy hover effect)

When you have enabled **IH small images zoom on hover**, the medium or large product image (depending on your setting for **IH small images zoom on hover size**) pops up on the storefront when hovering over a small product image.

**Note:**

1.     If you have enabled this feature, you might have found that the zoom feature can be disruptive to your customers using smaller, hand-held devices. The as-shipped `style_imagehover.css` includes examples using CSS3 Media Queries to control whether or not the image-hover is displayed. The defaults in that file disable the image-hover display on screen-sizes that are 480px or smaller in width.

For this feature to work on these product-listing pages:

1.     All Products
1.     New Products
1.     Featured Products
1.     Special Products

... these base Zen Cart **Configuration->Images** settings <span style="color: red;">must match</span> the respective **Small Image Width** and **Small Image Height** settings:

1.     Subcategory Image Width
1.     Subcategory Image Height
1.     Image - Product Listing Width
1.     Image - Product Listing Height
1.     Image - Product New Listing Width
1.     Image - Product New Listing Height
1.     Image - New Products Width
1.     Image - New Products Height
1.     Image - Featured Products Width
1.     Image - Featured Products Height
1.     Image - Product All Listing Width
1.     Image - Product All Listing Height

**Notes:**
1. If you want to add the hover effect to _category_ images, you must set *Category Icon Image Width - Product Info Pages* and *Category Icon Image Height - Product Info Pages* to match **Small Image Width** and **Small Image Height**, respectively.
2. If you selected `Medium` as your on-hover image, you control the size of that image via the **Product Info - Image Width** and **Product Info - Image Height** settings.
    - This value cannot be larger than your largest image (IH<sup>5</sup> does not _enlarge_ images) or smaller than your small images, i.e. its dimensions must fall *between* the small- and large-image settings.
3. If you selected `Large` as your on-hover image, you control the size of that image via the **IH large images maximum width/height**  settings.
    - If the largest image you upload is smaller than these dimensions, IH<sup>5</sup> **will not enlarge** your image. If the largest image you upload is larger than these dimensions, IH<sup>5</sup> will re-size your image based on these settings, maintaining the image's aspect ratio.
