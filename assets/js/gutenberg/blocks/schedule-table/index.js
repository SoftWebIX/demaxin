/**
 * Inernal dependencies.
 */
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
registerBlockType( 'demaxin/schedule-table', {
    title: __( 'Schedule', 'demaxin_test' ),
    icon: 'calendar',
    category: 'demaxin-blocks',
    keywords: [],
    edit: Edit,
    save: () => {
        return null;
    }
} );