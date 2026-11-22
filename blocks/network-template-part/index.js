// WordPress dependencies.
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import {
	Button,
	Disabled,
	PanelBody,
	SelectControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';
import ServerSideRender from '@wordpress/server-side-render';

// Internal dependencies.
import metadata from './block.json';

const Edit = (props) => {
	const {
		attributes: { slug, context },
		setAttributes,
	} = props;

	const { options, selectedTemplatePart } = useSelect(
		(select) => {
			const parts = select('core').getEntityRecords(
				'postType',
				'wp_template_part',
				{
					per_page: -1,
				}
			);

			const partOptions = parts
				? parts.map((part) => ({
						label: part.slug,
						value: part.slug,
					}))
				: [];

			partOptions.unshift({
				label: __('None', 'network-template-parts'),
				value: '',
			});

			// Find the selected template part to get its ID
			const selectedPart = parts?.find((part) => part.slug === slug);

			return {
				options: partOptions,
				selectedTemplatePart: selectedPart,
			};
		},
		[slug]
	);

	// Construct the site editor URL for the selected template part
	const getEditorUrl = () => {
		if (!selectedTemplatePart?.id) {
			return null;
		}

		return addQueryArgs('/wp-admin/site-editor.php', {
			postId: selectedTemplatePart.id,
			postType: 'wp_template_part',
		});
	};

	return (
		<div {...useBlockProps()}>
			<InspectorControls>
				<PanelBody
					title={__(
						'Template part options',
						'network-template-parts'
					)}
				>
					<SelectControl
						label={__('Template part', 'network-template-parts')}
						value={slug}
						options={options}
						onChange={(value) => setAttributes({ slug: value })}
					/>
					<SelectControl
						label={__('Context', 'network-template-parts')}
						value={context}
						options={[
							{
								label: 'Site',
								value: 'site',
							},
							{
								label: 'Network',
								value: 'network',
							},
						]}
						onChange={(value) => {
							setAttributes({ context: value });
						}}
					/>
					{slug && selectedTemplatePart && (
						<Button
							variant="secondary"
							href={getEditorUrl()}
							target="_blank"
							rel="noopener noreferrer"
						>
							{__('Edit template part', 'network-template-parts')}
						</Button>
					)}
				</PanelBody>
			</InspectorControls>
			<Disabled>
				<ServerSideRender
					block={metadata.name}
					attributes={props.attributes}
				/>
			</Disabled>
		</div>
	);
};

registerBlockType(metadata, {
	edit: Edit,
});
