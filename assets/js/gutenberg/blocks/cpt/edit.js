/**
 * Extarnal dependencies
 */
import Inspector from './inspector';
// import './editor.scss';

/**
 * WordPress dependencies
 */
const { serverSideRender: ServerSideRender } = wp;
const {
	Component,
	Fragment
} = wp.element;
const {
	Disabled
} = wp.components;
const { __ } = wp.i18n;

/**
 * Create an Component
 */
class Edit extends Component {
	
	constructor() {
		super( ...arguments );
	}

	render() {
		
		// const {
		//
		// } = this.props;
		
		return (
			<Fragment>
				<Inspector { ...{
					...this.props
				} } key="inspector" />
				<Disabled>
					<ServerSideRender
						block="demaxin/cpt"
						attributes={ this.props.attributes }
					/>
				</Disabled>
			</Fragment>
		);
	}
}

export default ( Edit );