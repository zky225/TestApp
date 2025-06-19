import { Icon, Card } from "../../components";
import { __ } from "@wordpress/i18n";
import { mainDemo, demo2, demo3, demo4, demo5 } from "../../components/images";

const StarterSites = () => {
    const cardList = [
        {
            heading: __('Vilva Pro', 'vilva'),
            imageurl: mainDemo,
            buttonUrl: __('https://blossomthemesdemo.com/vilva-pro/', 'vilva'),
        },
        {
            heading: __('Fashion', 'vilva'),
            imageurl: demo2,
            buttonUrl: __('https://blossomthemesdemo.com/vilva-fashion/', 'vilva'),
        },
        {
            heading: __('Travel', 'vilva'),
            imageurl: demo3,
            buttonUrl: __('https://blossomthemesdemo.com/vilva-pro-travel/', 'vilva'),
        },
        {
            heading: __('Parenting', 'vilva'),
            imageurl: demo4,
            buttonUrl: __('https://blossomthemesdemo.com/vilva-pro-parenting/', 'vilva'),
        },
        {
            heading: __('Recipe', 'vilva'),
            imageurl: demo5,
            buttonUrl: __('https://blossomthemesdemo.com/vilva-pro-recipe/', 'vilva'),
        },

    ]
    return (
        <>
            <Card
                cardList={cardList}
                cardPlace='starter'
                cardCol='three-col'
            />
            <div className="starter-sites-button cw-button">
                <a href={__( 'https://blossomthemes.com/theme-demo/?theme=vilva-pro&utm_source=vilva&utm_medium=dashboard&utm_campaign=theme_demo', 'vilva' )} target="_blank" className="cw-button-btn outline">
                    {__('View All Demos', 'vilva')}
                    <Icon icon="arrowtwo" />
                </a>
            </div>
        </>
    );
}

export default StarterSites;