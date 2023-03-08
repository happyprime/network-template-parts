// WordPress dependencies.
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { Disabled, PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

// Internal dependencies.
import metadata from './block.json';

const Edit = ( props ) => {
	const {
		attributes: { partSlug },
		setAttributes,
	} = props;

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={ __( 'Template part options', 'network-template-parts' ) }>
					<TextControl
						label="Template part slug"
						value={ partSlug }
						onChange={ ( value ) =>
							setAttributes( { partSlug: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<Disabled>
				<ServerSideRender
					block={ metadata.name }
					attributes={ props.attributes }
				/>
			</Disabled>
		</div>
	);
};

registerBlockType( metadata, {
	edit: Edit,
} );
