[Back](configuration.md "Return to the Configuration page")
# Background Color
You can configure the background color for every image size by specifying the color's RGB-value separating each color component (red, green and blue) by a colon. The color component's values each range from 0 to 255. The following examples should give you an idea how to set the background color.

-     `255:255:255` (white)
-     `255:0:0` (red)
-     `255:200:200` (light red)
-     `0:255:0` (green)
-     `0:0:255` (blue)
-     `255:0:255` (violet)
-     `255:255:0` (yellow)

Here are a few sources for obtaining RGB values. Color Schemer even provides a really nice HEX/RGB conversion calculator.

1.     http://rgbchart.com/
1.     http://cloford.com/resources/colours/500col.htm
1.     https://www.computerhope.com/htmcolor.htm

If you want to keep the transparency of an uploaded image, you need to set the background color value to transparent.

With **gif** images you can specify `transparent 255:255:255` instead of just `transparent` so the half-translucent pixels are combined with the specified background color. Pixels with transparency above 90% (this is the default) threshold are rendered fully transparent. If you set the latter color to the value of your page's background color, this does enhance visual quality of gifs substantially because of the reduction of stair-effects.