const AK = '1IVMiESjrDuFuBEdGMW1n8dOjG5KvQBd';
export default class MapService {
	constructor() {
	}

	static setMap () {
    return new Promise(function (resolve, reject) {
      window.init = function () {
        resolve(BMap)
      }
      var script = document.createElement("script");
      script.type = "text/javascript";
      script.src = "http://api.map.baidu.com/api?v=2.0&ak="+AK+"&callback=init";
      script.onerror = reject;
      document.head.appendChild(script);
    })
	}

}
