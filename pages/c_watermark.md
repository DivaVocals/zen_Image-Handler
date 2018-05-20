[Back](configuration.md "Return to the Configuration page")
# Watermarking
In order for watermarking to work for every image dimension, the specific corresponding watermark images must be present. That means you have to upload files to the following specific locations:

1. `images/watermark.png`
1. `images/medium/watermark_MED.png` (or your specified `MEDIUM_IMAGE_SUFFIX`)
1. `images/large/watermark_LRG.png` (or your specified `LARGE_IMAGE_SUFFIX`)

IH<sup>5</sup> includes some demo watermark images for small-, medium- and large-sized images featuring a slightly modified Zen-Cart logo for a quick start. Nothing more to do, switch on watermarks or switch off watermarks in the image settings just as you like and the images are generated accordingly. You can specify where you want the watermark to appear on the image canvas by your choice of **Watermark Gravity**, one of:
1.  NorthWest
1.  North
1.  NorthEast
1.  West
1.  Center
1.  East
1.  SouthWest
1.  South
1. SouthEast

To use your own watermark, simply create your own replacement watermark images using the image editing software of your choice and overwrite the supplied ones. Your custom watermark files must be PNGs and overwrite the sample watermark images. 

Please note that questions about how to create watermarks should not be posted on the support thread. There are tons of sources on the web which can provide you good guidance on creating watermarks, just Google `How to create watermarks`.
