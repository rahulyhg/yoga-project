const TYPE_OBJ = 'object';
const TYPE_ARRAY = 'array';
const TYPE_UNDEFINED = 'undefined';
const TYPE_STRING = 'string';
const TYPE_NUMBER = 'number';
const TYPE_NULL = 'null';

export default class TypeService {
    /**
     * 克隆对象 (解决对象绑定值遇到数据引用类型的问题)
     * @param obj
     * @returns {any}
     */
    static clone(obj) {
        if (obj === null || typeof (obj) !== 'object' || 'isActiveClone' in obj) {
            return obj;
        }

        let cloneObj = new obj.constructor;
        for (let attr in obj) {
            if (typeof obj[attr] === 'object') {
                cloneObj[attr] = this.clone(obj[attr]);
            } else {
                cloneObj[attr] = obj[attr];
            }
        }
        return cloneObj;
    }

    /**
     * 合并对象属性
     * @param setObj
     * @param newObj 需要合并到setObj的数据
     * @returns {any}
     */
    static mergeObj(setObj, newObj) {
        for (let property in newObj) {
            setObj[property] = newObj[property];
        }
        return setObj;
    }

    /**
     * 获取对象长度
     * @param obj
     */
    static getObjLength(obj) {
        let length = 0;
        if (obj) {
            for (let key in obj) {
                key = key;
                length++;
            }
        }
        return length;
    }

    /**
     * 获取数据长度
     * @param data
     */
    static getDataLength(data) {
        let length = 0;
        let type = (typeof data).toLocaleLowerCase();
        switch (type) {
            case TYPE_NULL:
                length = 0;
                break;
            case TYPE_ARRAY:
                length = data.length;
                break;
            case TYPE_OBJ:
                length = this.getObjLength(data);
                break;
            case TYPE_UNDEFINED:
                length = 0;
                break;
            case TYPE_STRING:
                length = data.length;
                break;
            case TYPE_NUMBER:
                data = data.toString();
                length = data.length;
                break;
            default:
                length = 1;
                break;

        }
        return length;
    }

    /**
     * 判断元素是否在数组内
     * @param search
     * @param array
     * @returns {boolean}
     */
    inArray(search, array) {
        let bol = false;
        for (let i in array) {
            if (array[i] === search) {
                bol = true;
                break;
            }
        }
        return bol;
    }

    /**
     * 判断数组中键是否存在
     * @param search
     * @param array
     * @returns {boolean}
     */
    isSetKey(search, array) {
        let bol = false;
        for (let i in array) {
            if (i === search) {
                bol = true;
                break;
            }
        }
        return bol;
    }


    /**
     * 判断数组中是否存在Key
     * @param search
     * @param array
     * @param key
     * @returns {boolean}
     */
    isArrayKey(search, array, key) {
        let arraySear;
        for (let i in array) {
            if (!!key) {
                arraySear = array[i][key];
            } else {
                arraySear = array[i];
            }
            if (arraySear === search) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断数组中值是否相等
     * @param searchArray
     * @param arrayKey
     * @param searchVal
     * @returns {boolean}
     */
    isArrayVal(searchArray, arrayKey, searchVal) {
        for (let val in searchArray) {
            if (searchArray[val][arrayKey] === searchVal) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取对象名
     */
    getObjName(obj) {
        let name = '';
        if (obj && obj.constructor) {
            name = obj.constructor.toString().match(/function\s*([^(]*)\(/)[1];
        }
        return name;

    }
}
