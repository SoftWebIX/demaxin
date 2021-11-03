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

/**
 * Create an Component
 */
class Edit extends Component {

    constructor() {
        super( ...arguments );
    }

    render() {

        return (
            <Fragment>
                <Disabled>
                    <ServerSideRender
                        block="demaxin/schedule-table"
                    />
                </Disabled>
            </Fragment>
        );
    }
}

export default ( Edit );