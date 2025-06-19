
import freevspro from "../../assets/img/freevspro.webp";
import { Sidebar, Icon } from "../../components";
import { __ } from '@wordpress/i18n';
const FreePro = () => {

    const sidebarSettings = [
        {
            heading: __('We Value Your Feedback!', 'vilva'),
            icon: "star",
            para: __("Your review helps us improve and assists others in making informed choices. Share your thoughts today!", 'vilva'),
            imageurl: <Icon icon="review" />,
            buttonText: __('Leave a Review', 'vilva'),
            buttonUrl: cw_dashboard.review
        },
        {
            heading: __('Knowledge Base', 'vilva'),
            para: __("Need help using our theme? Visit our well-organized Knowledge Base!", 'vilva'),
            imageurl: <Icon icon="documentation" />,
            buttonText: __('Explore', 'vilva'),
            buttonUrl: cw_dashboard.docmentation
        },
        {
            heading: __('Need Assistance? ', 'vilva'),
            para: __("If you need help or have any questions, don't hesitate to contact our support team. We're here to assist you!", 'vilva'),
            imageurl: <Icon icon="supportTwo" />,
            buttonText: __('Submit a Ticket', 'vilva'),
            buttonUrl: cw_dashboard.support
        }
    ];
    return (
        <>
            <div className="customizer-settings">
                <div className="cw-customizer">
                    <img className="freepro" src={freevspro} alt={__("Free vs Pro image", "vilva")} />
                </div>
                <Sidebar sidebarSettings={sidebarSettings} openInNewTab={true}/>
            </div>
        </>
    )
}

export default FreePro;