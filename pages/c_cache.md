[Back](configuration.md "Return to the Configuration page")
# Cache File-Naming Conventions
Starting with IH<sup>5</sup>, you can choose the naming convention used for re-sized files created by the image-handler, one of _Hashed_ or _Readable_.

## Hashed

This is the convention used by image-handler versions prior to 4.3.4. The handler uses an MD5 hash to compress the resized file's original path, name and parameters to produce file names similar to `8240eb50da20af3ecec990d3e56099fa.image.50x40.gif`. It can be "very difficult" to determine which original file is associated with that resized file!.

If you are currently using an Image Handler version prior to 4.3.4, this naming-convention will be initially configured on your IH upgrade. Some stores use HTML `img` tags in their category and/or product descriptions that reference an image's "hashed" name and this default is set to ensure that downward compatibility.

## Readable

This is the convention introduced image-handler versions later than 4.3.3. The handler concatenates the original file's path, name and parameters to produce file names similar to `matrox-mg400-32mbgif.image.50x40`. It's a little easier to determine which original file is associated with that resized filename!

If you are performing an initial install or upgrading Image Handler from a version later than 4.3.3, this naming will be initially configured for your IH<sup>5</sup> installation.
