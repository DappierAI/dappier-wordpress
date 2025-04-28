# Dappier for WordPress
Integrate Dappier AI on your WordPress site.

## Description

The Dappier plugin allows you to add an AI-powered AskAI module to your WordPress site. You can configure the module to auto-display or use it as a block or shortcode on any post or page.

## Installation

1. **Upload the Plugin**: Upload the `dappier-wordpress` directory to the `/wp-content/plugins/` directory.
2. **Activate the Plugin**: Activate the plugin through the 'Plugins' menu in WordPress.
3. **Initial Configuration**: Go to the Dappier settings page to create and configure your AI agent.

## Usage

### Auto-Display

- Enable the auto-display option in the settings to have the AskAI widget appear on all pages.

### Block Usage

- Add the Dappier AskAI block to any post or page using the block editor.

### Shortcode Usage

- Use the `[dappier-askai]` shortcode to insert the widget into posts or pages.

## Development

### Prerequisites

- Node.js and npm installed on your development machine.

### Steps

1. **Clone the Repository**: Clone the plugin repository to your local machine.
2. **Install Dependencies**: Run `npm install` to install the necessary dependencies.
3. **Build the Plugin**: Use `npm run build` to compile the JavaScript and CSS assets.
4. **Watch for Changes**: Use `npm run watch` to automatically rebuild assets when files change.

### File Structure

- `block/block.json`: Contains block metadata and configuration.
- `block/block.php`: Handles block registration and rendering.
- `block/index.js`: JavaScript entry point for the block editor.
- `build/`: Compiled assets are output here.

## Troubleshooting

- Ensure that the Dappier plugin is properly configured in the settings page.
- Check the console for any JavaScript errors if the widget is not displaying correctly.

## Agents

- **id**: The AI agent ID (the LLM). This is `aimodel_id` in the dappier options array values.
- **datamodel_id**: The internal data model ID, typically not exposed. This is used as the authorization header for WordPress feed ingestion. This is `datamodel_id` in the dappier options array values.
- **external_dm_id**: The externalized data model ID. Use this ID when making search calls for semantic results. This is `external_dm_id` in the dappier options array values.
- **widget_id**: The ID of the AskAI widget, which can be embedded on a page and is associated with a specific agent ID. This is `widget_id` in the dappier options array values.

## Support

For support, please visit the [Dappier Support Page](https://docs.dappier.com/wordpress).
