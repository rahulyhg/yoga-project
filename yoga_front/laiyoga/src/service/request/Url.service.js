import appRequest from '../../config/request.config'

export default class UrlService {
	constructor() {
	}

	static getLinkUrl(url) {
		url = typeof url !== 'undefined' ? url : '';
		let baseUrl = appRequest.SUB_DIR !== '' ? '/' + appRequest.SUB_DIR : '/';
		return baseUrl + '#' + url;
	}

}
