import { useState } from 'react';
import { Icon, Tab } from '../components';
import FreePro from './FreePro';
import Homepage from "./Home";
import Offers from './Offers';
import UsefulPlugins from './UsefulPlugins';
import FAQ from './FAQ';
import { __ } from '@wordpress/i18n';
import StarterSites from './StarterSites';

function Dashboard() {
    const [activeTabTitle, setActiveTabTitle] = useState('Home');

    const tabsData = [
        {
            title: __( 'Home', 'vilva' ),
            icon: <Icon icon="home" />,
            content: <Homepage />
        },
        {
            title: __( 'Starter Sites', 'vilva' ),
            icon: <Icon icon="globe" />,
            content: <StarterSites />
        },
        {
            title: __( 'Free vs Pro', 'vilva' ),
            icon: <Icon icon="freePro" />,
            content: <FreePro />
        },
        {
            title: __( 'Offers', 'vilva' ),
            icon: <Icon icon="offers" />,
            content: <Offers />
        },
        {
            title: __( 'FAQs', 'vilva' ),
            icon: <Icon icon="support" />,
            content: <FAQ />
        },
        {
            title: __( 'Useful Plugins', 'vilva' ),
            icon: <Icon icon="plugins" />,
            content: <UsefulPlugins />
        }
    ];

    const handleTabChange = (title) => {
        setActiveTabTitle(title);
    };

    return (
        <>
            <Tab
                tabsData={tabsData}
                onChange={handleTabChange}
                activeTabTitle={activeTabTitle}
            />
        </>
    );
}

export default Dashboard;
