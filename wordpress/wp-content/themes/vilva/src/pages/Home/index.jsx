import { Icon, Sidebar, Card, Heading } from "../../components";
import { __ } from '@wordpress/i18n';

const Homepage = () => {
    const cardLists = [
        {
            iconSvg: <Icon icon="site" />,
            heading: __('Site Identity', 'vilva'),
            buttonText: __('Customize', 'vilva'),
            buttonUrl: cw_dashboard.custom_logo
        },
        {
            iconSvg: <Icon icon="colorsetting" />,
            heading: __("Color Settings", 'vilva'),
            buttonText: __('Customize', 'vilva'),
            buttonUrl: cw_dashboard.colors
        },
        {
            iconSvg: <Icon icon="layoutsetting" />,
            heading: __("Layout Settings", 'vilva'),
            buttonText: __('Customize', 'vilva'),
            buttonUrl: cw_dashboard.layout
        },
        {
            iconSvg: <Icon icon="instagramsetting" />,
            heading: __("Instagram Settings", 'vilva'),
            buttonText: __('Customize', 'vilva'),
            buttonUrl: cw_dashboard.instagram
        },
        {
            iconSvg: <Icon icon="generalsetting" />,
            heading: __("General Settings"),
            buttonText: __('Customize', 'vilva'),
            buttonUrl: cw_dashboard.general
        },
        {
            iconSvg: <Icon icon="footersetting" />,
            heading: __('Footer Settings', 'vilva'),
            buttonText: __('Customize', 'vilva'),
            buttonUrl: cw_dashboard.footer
        }
    ];

    const proSettings = [
        {
            heading: __('Header Layouts', 'vilva'),
            para: __('Choose from different unique header layouts.', 'vilva'),
            buttonText: __('Learn More', 'vilva'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('Multiple Layouts', 'vilva'),
            para: __('Choose layouts for blogs, banners, posts and more.', 'vilva'),
            buttonText: __('Learn More', 'vilva'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('Multiple Sidebar', 'vilva'),
            para: __('Set different sidebars for posts and pages.', 'vilva'),
            buttonText: "Learn More",
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            heading: __('Top Bar Settings', 'vilva'),
            para: __('Show a notice or newsletter at the top.', 'vilva'),
            buttonText: __('Learn More', 'vilva'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Boost your website performance with ease.', 'vilva'),
            heading: __('Performance Settings', 'vilva'),
            buttonText: __('Learn More', 'vilva'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Choose typography for different heading tags.', 'vilva'),
            heading: __('Typography Settings', 'vilva'),
            buttonText: __('Learn More', 'vilva'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Import the demo content to kickstart your site.', 'vilva'),
            heading: __('One Click Demo Import', 'vilva'),
            buttonText: __('Learn More', 'vilva'),
            buttonUrl: cw_dashboard?.get_pro
        },
        {
            para: __('Easily place ads on high conversion areas.', 'vilva'),
            heading: __('Advertisement Settings', 'vilva'),
            buttonText: __('Learn More', 'vilva'),
            buttonUrl: cw_dashboard?.get_pro
        },
    ];

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
                    <div className="video-section">
                        <div className="cw-settings">
                            <h2>{__('Vilva Tutorial', 'vilva')}</h2>
                        </div>
                        <iframe src="https://www.youtube.com/embed/SibyfngPLNI" title={__( 'How To Start A Blog | STEP-BY-STEP Tutorial | Vilva WordPress Theme', 'vilva' )} frameBorder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerPolicy="strict-origin-when-cross-origin" allowFullScreen></iframe>
                    </div>
                    <Heading
                        heading={__( 'Quick Customizer Settings', 'vilva' )}
                        buttonText = {__('Go To Customizer', 'vilva')}
                        buttonUrl={cw_dashboard?.customizer_url}
                        openInNewTab={true}
                    />
                    <Card
                        cardList={cardLists}
                        cardPlace='customizer'
                        cardCol='three-col'
                    />
                    <Heading
                        heading={__( 'More features with Pro version', 'vilva' )}
                        buttonText={__('Go To Customizer', 'vilva')}
                        buttonUrl={cw_dashboard?.customizer_url}
                        openInNewTab={true}
                    />
                    <Card
                        cardList={proSettings}
                        cardPlace='cw-pro'
                        cardCol='two-col'
                    />x
                    <div className="cw-button">
                        <a href={cw_dashboard?.get_pro} target="_blank" className="cw-button-btn primary-btn long-button">{__('Learn more about the Pro version', 'vilvax`1')}</a>
                    </div>
                </div>
                <Sidebar sidebarSettings={sidebarSettings} openInNewTab={true} />
            </div>
        </>
    );
}

export default Homepage;