/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const {
	Component,
	Fragment
} = wp.element;
const {
	InspectorControls
} = wp.blockEditor || wp.editor;
const {
	PanelBody,
	RangeControl,
	ToggleControl
} = wp.components;

// <RangeControl
// 	label={ __( 'Number of items', 'demaxin_test' ) }
// 	value={ postsToShow }
// 	onChange={ number => {
// 		setAttributes( { postsToShow: number } );
// 	} }
// 	min={ 0 }
// 	max={ 9 }
// 	step={ 1 }
// />

class Inspector extends Component {
	
	constructor() {
		super( ...arguments );
	}
	
	render() {
		const {
			attributes: {
				postsToShow,
				pagination
			},
			setAttributes
		} = this.props;
		
		return (
			<InspectorControls>
				<PanelBody title={ __( 'Settings', 'demaxin_test' ) } initialOpen={ true }>
					<ToggleControl
						label={ __( 'Use pagination', 'demaxin_test' ) }
						checked={ pagination ? pagination : false }
						onChange={ value => {
							setAttributes( { pagination: !pagination } );
						} }
					/>
				</PanelBody>
			</InspectorControls>
		);
	}
}

export default ( Inspector );