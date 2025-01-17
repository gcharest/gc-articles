import { __ } from "@wordpress/i18n";
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody, Disabled, TextControl } from '@wordpress/components';
import { useBlockProps } from '@wordpress/block-editor';
import { name } from '../block.json';

const Edit = ({ attributes, setAttributes }) => {
	const { placeholderValue } = attributes;
	const blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<Disabled>
				<ServerSideRender block={name} attributes={attributes} />
			</Disabled>
		</div>
	);
};

export default Edit;
