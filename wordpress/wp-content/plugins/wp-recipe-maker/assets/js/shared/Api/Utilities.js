const utilitiesEndpoint = wprm_admin.endpoints.utilities;
import ApiWrapper from '../ApiWrapper';

export default {
    saveImage(url) {
        const data = {
            url,
        };

        return ApiWrapper.call( `${utilitiesEndpoint}/save_image`, 'POST', data );
    },
    giveFeedback(feedback) {
        const data = {
            feedback,
        };

        return ApiWrapper.call( `${utilitiesEndpoint}/feedback`, 'POST', data );
    },
    getPostSummary(id) {
        return ApiWrapper.call( `${utilitiesEndpoint}/post_summary/${id}` );
    },
    previewRecipe(json) {
        const data = {
            json,
        }
        return ApiWrapper.call( `${utilitiesEndpoint}/preview`, 'POST', data );
    },
};
