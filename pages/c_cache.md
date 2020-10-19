[Back](configuration.md "Return to the Configuration page")
# Cache File-Naming Conventions
Starting with IH<sup>5</sup>, you can choose the naming convention used for re-sized files created by the image-handler, one of _Hashed_,  _Readable_ or _Mirrored_.

## Hashed

This is the convention used by image-handler versions prior to 4.3.4. The handler uses an MD5 hash to compress the resized file's original path, name and parameters to produce file names similar to `8240eb50da20af3ecec990d3e56099fa.image.50x40.gif` in the directory `bmz_cache\8`. It can be "very difficult" to determine which original file is associated with that resized file!.

If you are currently using an Image Handler version prior to 4.3.4, this naming-convention will be initially configured on your IH upgrade. Some stores use HTML `img` tags in their category and/or product descriptions that reference an image's "hashed" name and this default is set to ensure that downward compatibility.

## Readable

This is the convention introduced image-handler versions later than 4.3.3. The handler concatenates the original file's path, name and parameters to produce file names similar to `matrox-mg400-32mbgif.image.50x40` in the directory `bmz_cache\m`. It's a little easier to determine which original file is associated with that resized filename!

If you are performing an initial install or upgrading Image Handler from a version later than 4.3.3, this naming will be initially configured for your IH<sup>5</sup> installation.

## Mirrored

This is a convention introduced with image-handler version 5.1.9. The handler concatenates the original name and parameters to produce file names similar to  _Readable_.  The difference is that instead of storing the files under a single letter directory (the first character of the file name), The files are stored in a mirror of the original directory structure. This is useful if you have many images that start with the same letter allowing them to be spread through directories as the original files.

For example:
If you have a file called `matrox.gif` in the directory `images\Graphics\cards` You would create a file like `matrox-mg400-32mbgif.image.50x40.gif` in the directory `bmz_cache\Graphics\cards`
