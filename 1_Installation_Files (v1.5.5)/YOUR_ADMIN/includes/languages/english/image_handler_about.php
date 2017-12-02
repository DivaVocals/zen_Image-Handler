<?php
// -----
// Part of the "Image Handler" plugin, v5.0.0 and later, by Cindy Merkin a.k.a. lat9 (cindy@vinosdefrutastropicales.com)
// Copyright (c) 2017 Vinos de Frutas Tropicales
//
// This is not a "traditional" Zen Cart language file.  Its purpose is to provide the information associated with
// the admin's Tools->Image Handler's "About/Help" link and is included by that module when/if that link is active.
//
?>
<div class="aboutbox">
    <h2>Image Handler<sup>5</sup> for Zen Cart 1.5.5 and later</h2>
    <p>Image Handler<sup>5</sup> is based on an original contribution by Tim Kr&#246;ger.</p>
    <fieldset>
        <legend>Purpose &amp; Aim</legend>
        <p>Image Handler<sup>5</sup>, at the heart of its code, is really meant to ease the management of product images (particularly the management of additional product images), and to help improve page performance by optimizing the product images.</p>
        <p>IH<sup>5</sup> generates product images (based on your image settings) in your store's <code>/bmz_cache</code> folder. It <strong>does not</strong> replace or modify the original images, so it's <em>perfectly</em> safe to use on an existing store.</p>
        <p>The plugin enables you to use GD libraries or ImageMagick (if installed on your server) to generate and resize small, medium and large images on-the-fly/on page request. You can simply upload just one large image (that gets resized as required) or you can have different sources for medium and large images. You can also watermark your images on-the-fly (overlay a second specific translucent image onto the original) and have medium or large images pop up when you move your mouse over a small image (fancy hover).</p>
        <p>This contribution includes a powerful admin interface to browse your products just like you would with the Attribute Manager and upload, delete or add additional images without having to do this manually via <acronym title="File Transfer Protocol">FTP</acronym>. IH<sup>5</sup> works fine with mass update utilities like EZ-Populate and the Database I/O Manager.</p>
    </fieldset>
    <hr />
    <fieldset>
        <legend>Features</legend>
        <ul>
          <li>Improves site performance (faster loading, faster display)</li>
          <li>Professional looking images (no stair-effects, smooth edges)</li>
          <li>Choose preferred image-types for each image size</li>
          <li>Uploading one image automatically creates small, medium and large images on page request</li>
          <li>Drops in and out seamlessly. No need to redo your images. All images are kept.</li>
          <li>Easy install. One-click-database-upgrade.</li>
          <li>Works with mass-update/-upload tools like EZ-Populate and the Database I/O Manager.</li>
          <li>Watermark images to prevent competitors from stealing them. (prove ownership)</li>
          <li>Fancy image hover functionality lets a larger image pop up whenever you move your mouse above a small image (optional).</li>
          <li>Choose an image background color to match your site or select a transparent background for your images.</li>
          <li>Manage multiple images for products easily from one page just like you do with attributes in the Products Attribute Manager.</li>
        </ul>
        <p>IH<sup>5</sup> is meant to ease the work required to setup images for your store. It works <em>with</em> the default Zen Cart functionality, it does not replace it.</p>
        <p>It is very strongly recommend you read through the ENTIRE "<strong>Configuration</strong>" &amp; "<strong>Usage</strong>" sections of the Image Handler<sup>4</sup> readme file. There you will find out exactly what <strong>Image Handler<sup>5</sup></strong> can do.</p>
    </fieldset>

    <hr />
    <fieldset>
        <legend>Troubleshooting Basics</legend>
        <p>Make sure your custom template is active. (Admin &gt; Tools &gt; Template Selection)</p>
        <p>Make sure Image Handler<sup>5</sup> is installed. <strong>Admin &gt; Tools &gt; Image Handler<sup>4</sup> &gt; Admin</strong>.  Set permissions in both your <strong>images</strong> and <strong>bmz_cache</strong> folders to 755 (eg: <strong>both </strong>of these folders need to have  the same permissions. For some webhosts you may have to set these permissions to 777).</p>
        <p>If Image Handler<sup>5</sup> does not work or gives you errors:</p>
        <ul>
          <li>Make sure all files are in correct location</li>
          <li>Make sure you uploaded ALL the Image Handler<sup>5</sup> files</li>
          <li>Make sure the files are not corrupt from bad FTP transfers</li>
          <li>Make sure your file merge edits are correct</li>
          <li>MAKE SURE YOU RE-READ THE CONFIGURATION AND USAGE SECTIONS!!!</li>
          <li>Make sure that there are no javascript conflicts (this last point has been largely addressed since Rev 7)</li>
          <li>Make sure that your main product image files names DO NOT contain any special characters (<font>non-alphanumeric characters such as / \ : ! @ # $ % ^ &lt; &gt; , [ ] { } &amp; * ( ) + = </font>). Always use proper filenaming practices when naming your images.</li>
        </ul>
    </fieldset>

    <hr />
    <fieldset>
        <legend>Zen Cart and Image Management</legend>
        <p>Image Handler<sup>5</sup> is meant to ease the work required to setup images for your store. It works <em>with</em> the default Zen Cart functionality, it does not replace it. Here are some additional FAQs which discuss how product images work in Zen Cart.</p>
        <ul>
          <li><a href="https://www.zen-cart.com/content.php?223" target="_blank">Image Preparation - How-to</a></li>
          <li><a href="https://www.zen-cart.com/content.php?72" target="_blank">My images are distorted/fuzzy/squished, help?</a></li>
        </ul>
        <p>Information on how Zen Cart identifies/manages additional product images can be found on these Zen Cart FAQs:</p>
        <ul>
          <li><a href="https://www.zen-cart.com/content.php?273" target="_blank">Why am I seeing images for other products on my product pages?</a></li>
          <li><a href="hhttps://www.zen-cart.com/content.php?100" target="_blank">How do I add multiple images to a product?</a></li>
          <li><a href="https://www.zen-cart.com/content.php?100" target="_blank">How do I add more than one image of a product?  I want to have a main image and also one or two other images that show more parts of a particular product. How/where do I add additional images to a product page?</a></li>
        </ul>
        <p>Check out these FAQs and see if they help clarify how Zen Cart works with product images.</p>
    </fieldset>

    <hr />
    <fieldset>
        <legend> Prepare Your Site for Growth</legend>
        <p>Not many users are aware that Image Handler<sup>5</sup> can manage the needs of a very large site as easily as it does a small one. When first building a site, the owner of a small site needs only to load images to the images folder. But when the site gets bigger and images multiply like rabbits, this can cause file naming confusions for Zen Cart and slow down the site. Preparing for your business to grow from the beginning will save you hours of work later on!</p>
        <p>Without IH<sup>5</sup> installed, Zen Cart requires you to create, optimize, and upload three different size images for each image you want to use. You must name these images using naming suffixes and place them in corresponding folders inside your main <code>/images</code> folder. For example: A product called &quot;Widget&quot; requires images/widget.jpg (small image) images/medium/widget_MED.jpg (medium image) and images/large/widget_LRG.jpg. This is such a hassle, especially if many of your products have multiple images. And as your site grows, it becomes an impossible task!</p>
        <p>With IH<sup>5</sup>, you no longer have to make three sizes of the same images and place them in different folders (unless you want to)! Instead, you need upload only one image in one folder and IH<sup>5</sup> will do the rest! Simply upload your largest highest quality image and IH<sup>5</sup> will resize and optimize your image as needed, and serve up small, medium, or large image sizes appropriate to the page loaded - all automatically and all without actually modifying your original image file in any way! Check out the Configuration Tab of this ReadMe for more info about this awesome functionality!</p>
        <p>Prepare your site for growth by simply creating sub-folders in your main images folder. For example, you may want to put all your &quot;widget&quot; images in a folder called &quot;widgets&quot; and all your doodad images in a folder called &quot;doodads&quot; , like this:</p>
        <p>Product: Blue Widget with 3 images<br>
          ---------------------------------- <br>
          /images/widgets/blue_widget1.jpg (main product image for a blue widget, i.e. front view)<br>
          /images/widgets/blue_widget2.jpg (additional product image for a blue widget, i.e. side view)<br>
          /images/widgets/blue_widget3.jpg (additional product image for a blue widget, i.e. rear view)</p>
        <p>&nbsp;</p>
        <p>Product: Red Widget with 1 image<br>
          --------------------------------<br>
          /images/widgets/red_widget.jpg (main product image for a red widget)</p>
        <p>&nbsp;</p>
        <p>Product: Gold Doodad with 2 images<br>
          ----------------------------------<br>
          /images/doodads/gold_doodad1.jpg (main product image for a gold doodad, i.e. view from above)<br>
          /images/doodads/gold_doodad2.jpg (additional product image for a gold doodad, i.e. view from side)</p>
        <p>&nbsp;</p>
        <p>Product: Silver Doodad with 3 images<br>
          ------------------------------------<br>
          /images/doodads/silver_doodad1.jpg (main product image for a silver doodad, i.e. product)<br>
          /images/doodads/silver_doodad2.jpg (additional product image for a silver doodad, i.e. product detail)<br>
          /images/doodads/silver_doodad3.jpg (additional product image for a silver doodad, i.e. product's silver stamp)<br>
        </p>
        <p>Using Image Handler<sup>5</sup>, you can easily sort and manage thousands of images without confusion or hassle! When selecting the main image for a product in the IH<sup>5</sup> interface, the plugin lets you pick the location for this image. This prompt disappears afterwards because IH<sup>5</sup> knows that additional images need to be in the same folder as their main product image and handles that automatically!</p>
    </fieldset>
</div>