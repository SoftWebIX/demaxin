/**
 * Inernal dependencies.
 */
import attributes from './attributes';
import Edit from './edit';
import './style.scss';

/**
 * External dependencies
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

/**
 * Register the block
 */
registerBlockType( 'demaxin/cpt', {
	title: __( 'Custom Post Type', 'demaxin_test' ),
	icon: 'admin-post',
	category: 'demaxin-blocks',
	keywords: [],
	attributes,
	edit: Edit,
	save: () => {
		return null;
	}
} );