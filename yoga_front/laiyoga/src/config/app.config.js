const isProd = true;
/*const subDir = ''; //预发，正式*/

const subDir = '';
//请求配置
const domain = isProd?'http://www.hcforce.com/':'http://localhost:8086/';
const resourceDomain = isProd?'/':'http://localhost:8000/';
const apiDomain = '/';
const requestByDomain = 1;
const debug = true;

export default {
  isProd:isProd,
  debug: debug,
  locale: 'zh-CHS',
  domain: domain + subDir,
  apiDomain: apiDomain + subDir,
  resourceDomain: resourceDomain + subDir,
  requestByDomain: requestByDomain,
  subDir: subDir

};
