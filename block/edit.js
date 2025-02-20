/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls, PanelColorSettings } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	ToggleControl,
	TabPanel,
	Icon,
} from '@wordpress/components';
import { cog, styles } from '@wordpress/icons';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const {
		mainBackgroundColor,
		themeColor,
		promptSuggestionBackgroundColor,
		promptSuggestionTextColor,
		askButtonTextColor,
		askButtonBackgroundColor,
		searchPlaceholderText,
		askButtonText,
		enablePromptSuggestions,
		enableContentRecommendations,
		showInitialSearchQuery,
	} = attributes;

	const tabs = [
		{
			icon: <Icon icon={cog} size={24} />,
			name: 'settings',
			title: __('Settings', 'dappier-wordpress'),
			className: 'tab-one',
		},
		{
			icon: <Icon icon={styles} size={24} />,
			name: 'styles',
			title: __('Styles', 'dappier-wordpress'),
			className: 'tab-two',
		},
	];

	return (
		<>
			<InspectorControls>
				<TabPanel
					className="dappier-inspector-tabs"
					activeClass="is-active"
					tabs={tabs}
				>
					{(tab) => {
						if (tab.name === 'settings') {
							return (
								<PanelBody>
									<TextControl
										label={__('Search Placeholder Text', 'dappier-wordpress')}
										value={searchPlaceholderText}
										onChange={(value) => setAttributes({ searchPlaceholderText: value })}
									/>
									<TextControl
										label={__('Ask Button Text', 'dappier-wordpress')}
										value={askButtonText}
										onChange={(value) => setAttributes({ askButtonText: value })}
									/>
									<ToggleControl
										label={__('Show Prompt Suggestions', 'dappier-wordpress')}
										checked={enablePromptSuggestions}
										onChange={(value) => setAttributes({ enablePromptSuggestions: value })}
									/>
									<ToggleControl
										label={__('Show Content Recommendations', 'dappier-wordpress')}
										checked={enableContentRecommendations}
										onChange={(value) => setAttributes({ enableContentRecommendations: value })}
									/>
									<ToggleControl
										label={__('Show Initial Search Query', 'dappier-wordpress')}
										checked={showInitialSearchQuery}
										onChange={(value) => setAttributes({ showInitialSearchQuery: value })}
									/>
								</PanelBody>
							);
						}
						return (
							<PanelColorSettings
								title={__('Color Settings', 'dappier-wordpress')}
								colorSettings={[
									{
										value: mainBackgroundColor,
										onChange: (color) => setAttributes({ mainBackgroundColor: color }),
										label: __('Main Background', 'dappier-wordpress')
									},
									{
										value: themeColor,
										onChange: (color) => setAttributes({ themeColor: color }),
										label: __('Accent Color', 'dappier-wordpress')
									},
									{
										value: promptSuggestionBackgroundColor,
										onChange: (color) => setAttributes({ promptSuggestionBackgroundColor: color }),
										label: __('Prompt Suggestion Background', 'dappier-wordpress')
									},
									{
										value: promptSuggestionTextColor,
										onChange: (color) => setAttributes({ promptSuggestionTextColor: color }),
										label: __('Prompt Suggestion Text', 'dappier-wordpress')
									},
									{
										value: askButtonTextColor,
										onChange: (color) => setAttributes({ askButtonTextColor: color }),
										label: __('Ask Button Text', 'dappier-wordpress')
									},
									{
										value: askButtonBackgroundColor,
										onChange: (color) => setAttributes({ askButtonBackgroundColor: color }),
										label: __('Ask Button Background', 'dappier-wordpress')
									}
								]}
							/>
						);
					}}
				</TabPanel>
				<div style={{ padding: '0 16px 16px' }}>
					<p style={{ marginBottom: '8px' }}>
						<a href={'/wp-admin/admin.php?page=dappier'}>
							{__('Dappier Settings →', 'dappier-wordpress')}
						</a>
					</p>
				</div>
			</InspectorControls>

			<div {...useBlockProps()}>
				<ServerSideRender
					block="dappier/askai"
					attributes={attributes}
				/>
			</div>
		</>
	);
}
