// WordPress dependencies.
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { Disabled, PanelBody, SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

// Internal dependencies.
import metadata from './block.json';

const Edit = ( props ) => {
	const {
		attributes: { slug },
		setAttributes,
	} = props;

	const { options } = useSelect( ( select ) => {
		const parts = select( 'core' ).getEntityRecords(
			'postType',
			'wp_template_part',
			{
				per_page: -1,
			}
		);

		const partOptions = parts
			? parts
					.filter( ( part ) =>
						part.slug.startsWith( 'network-templates-' )
					)
					.map( ( part ) => ( {
						label: part.slug.slice( 18 ),
						value: part.slug.slice( 18 ),
					} ) )
			: [];

		partOptions.unshift( {
			label: __( 'None', 'network-template-parts' ),
			value: '',
		} );

		return {
			options: partOptions,
		};
	}, [] );

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody
					title={ __(
						'Template part options',
						'network-template-parts'
					) }
				>
					<SelectControl
						label={ __(
							'Template',
							'network-template-parts'
						) }
						value={ slug }
						options={ options }
						onChange={ ( value ) =>
							setAttributes( { slug: value } )
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
