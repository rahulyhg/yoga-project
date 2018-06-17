const state = {
    page: {
        isLoadContent: false, //是否加载过页面
    }
}

const mutations = {
    setLoadContentStatus(state, isLoad) {
        state.page.isLoadContent = isLoad;
    }
}

const getters = {
    loadContentStatus(state) {
        return state.page.isLoadContent
    }
}

export default {
    state,
    mutations,
    getters
}
