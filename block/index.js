/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( metadata.name, {
	...metadata,
	icon: {
		src: <svg
			viewBox="0 0 137.44 160"
			xmlns="http://www.w3.org/2000/svg"
		>
			<path fill="none" stroke="currentColor" strokeWidth="5" d="M42.17,22.98l31.46,35.65"/>
			<path fill="none" stroke="currentColor" strokeWidth="5" d="M123.6,24.41l-16.78,33.56"/>
			<path fill="none" stroke="currentColor" strokeWidth="5" d="M31.46,84.65h27.26"/>
			<path fill="none" stroke="currentColor" strokeWidth="5" d="M77.91,147.84l10.49-37.75"/>
			<path fill="currentColor" d="M115.35,19.23c0,5.79,4.69,10.49,10.49,10.49c5.79,0,10.49-4.69,10.49-10.49s-4.7-10.49-10.49-10.49S115.35,13.44,115.35,19.23z"/>
			<path fill="currentColor" d="M67.11,149.27c0,5.79,4.69,10.49,10.49,10.49s10.49-4.7,10.49-10.49s-4.69-10.49-10.49-10.49S67.11,143.48,67.11,149.27z"/>
			<path fill="currentColor" d="M20.62,25.17c0,13.9,11.27,25.17,25.17,25.17s25.17-11.27,25.17-25.17S59.69,0,45.78,0S20.62,11.27,20.62,25.17z"/>
			<path fill="currentColor" d="M0,84.25c0,9.27,7.51,16.78,16.78,16.78s16.78-7.51,16.78-16.78s-7.51-16.78-16.78-16.78S0,74.98,0,84.25z"/>
			<path fill="currentColor" d="M122.95,54.98c8,0,14.49,6.2,14.49,13.86v33.39c0,5.91-5.01,10.71-11.2,10.71c-1.82,0-3.29,1.41-3.29,3.15 v10.71c0,1.48-0.91,2.83-2.32,3.44c-1.41,0.61-3.07,0.38-4.23-0.6l-18.1-15.14c-1.22-1.01-2.75-1.56-4.34-1.56H72.9 c-8,0-14.49-6.21-14.49-13.86V68.84c0-7.65,6.49-13.86,14.49-13.86C72.9,54.98,122.95,54.98,122.95,54.98z"/>

		</svg>
	},
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
	save: () => null
} );
