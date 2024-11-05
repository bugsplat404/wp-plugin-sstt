
# === Simple Scroll To Top ===

# Structure

```
wp-content
└── plugins
    └── simple-scroll-to-top
        ├── simple-scroll-to-top.php
        ├── css
        │   └── sstt-style.css
        ├── js
        │   └── sstt-script.js
        └── readme.md
```

# Another Simple Scroll To Top Plugin

A simple, configurable scroll-to-top button plugin for WordPress. This plugin adds a customizable "Scroll to Top" button to your website, allowing users to easily navigate back to the top of the page. It requires the [CMB2](https://wordpress.org/plugins/cmb2/) plugin for configuration options.

## Features

- **Smooth Scroll To Top**: Adding a button to the bottom of your WP Page to smoothly scroll to the top
- **Customization**: Adjust all dimensions and looks of the button with a simple interface

## Installation

1. **Download the Plugin**: Clone or download the repository from [GitHub](https://github.com/bugsplat404/wp-plugin-sstt).

2. **Upload to WordPress**: Upload the `simple-scroll-to-top` folder to the `/wp-content/plugins/` directory.

3. **Install Required Plugin**: Install and activate the [CMB2](https://wordpress.org/plugins/cmb2/) plugin, which is required for the configuration options.

4. **Activate the Plugin**: In the WordPress admin panel, navigate to **Plugins** > **Installed Plugins** and activate **Simple Scroll To Top**.

## Configuration

After activating the plugin, you can customize its settings:

1. Navigate to **Settings** > **Simple Scroll To Top** in the WordPress admin panel.

2. Adjust the following settings to your preference:

   - **📍 Position**: Choose between bottom right or bottom left placement.
   - **🎨 Button Color**: Select the button's background color.
   - **✍️ Hover Color**: Choose the color when the button is hovered over.
   - **📏 Size (px)**: Set the button's size in pixels.
   - **⭕ Shape**: Select the shape of the button (circle, square, rounded rectangle).
   - **🖼️ Icon**: Choose a default icon or upload a custom one.
   - **🛫 Scroll Speed (ms)**: Define the speed of the scroll animation in milliseconds.
   - **👁️ Display Threshold (px)**: Set how many pixels the user must scroll before the button appears.
   - **↕️ Bottom Distance (px)**: Adjust the distance from the bottom of the screen.
   - **↔️ Side Distance (px)**: Adjust the distance from the side of the screen.
   - **📝 Tooltip Text**: Enter the text to display when hovering over the button.
   - **💥 Animation Effect**: Select the appearance/disappearance animation (slide, fade, none).
   - **📱 Hide on Mobile Devices**: Option to hide the button on mobile devices.
   - **🗂️ Z-Index**: Set the z-index for stacking order control.

3. **Save Changes**: Click **Save Changes** to apply the settings.

## Requirements

- **WordPress Version**: Tested up to 6.6.2.
- **PHP Version**: Tested on PHP 8.2.
- **Required Plugin**: [CMB2](https://wordpress.org/plugins/cmb2/).


![Config Image](https://github.com/bugsplat404/wp-plugin-sstt/blob/main/doc-img.png "Config Image")


## Notes

- Ensure that jQuery is loaded on your site, as it is required for the button's functionality.
- When uploading a custom icon, use an image with appropriate dimensions to fit within the button.
- Using external resources (like FontAwesome for icons) is planned for the future

## Future Enhancements

- **Additional Icon Libraries**: Integrate popular icon libraries for more icon choices.
- **Multi-language Support**: Add localization for multiple languages.
- **Advanced Animations**: Provide more animation effects using CSS3 or JavaScript libraries.
- **Custom CSS**: Allow users to add custom CSS directly from the settings page.

## License

This plugin is open-source and freely available for use and modification.

---

For any issues or feature requests, please visit the [GitHub repository](https://github.com/bugsplat404/wp-plugin-sstt).
