import {createStore} from "vuex";
import {UserTypeDTO} from "../dto/User.dto";

export type State = {
    showMenu: boolean,
    user: UserTypeDTO
};

export const store = createStore({
    state: <State>{
        showMenu: false,
        user: {} as UserTypeDTO
    },
    mutations: {
        toggleMenu(state): void {
            state.showMenu = !state.showMenu;
        },

        setUser(state, payload: UserTypeDTO): void {
            state.user = payload

            const user = JSON.stringify(payload);

            localStorage.setItem('user', user);
        },

        setBalance(state, payload: number): void {
            state.user.balance = payload;

            const user = JSON.stringify(state.user);

            localStorage.setItem('user', user);
        },
    },
    actions: {},
    getters: {},
    modules: {}
})
