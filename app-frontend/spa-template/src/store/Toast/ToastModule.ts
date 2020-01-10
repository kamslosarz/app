import {Action, Module, Mutation, VuexModule} from "vuex-module-decorators";
import {ToastMessage} from "@/models/ToastMessage";

@Module({
  namespaced: true
})
export default class ToastModule extends VuexModule {
  messages!: ToastMessage[];

  @Mutation
  removeMessage(toastMessage: ToastMessage) {
    this.messages = this.messages.filter(message => {
      return message !== toastMessage;
    });
  }

  @Mutation
  addMessage(toastMessage: ToastMessage) {
    this.messages.push(toastMessage);
  }

  @Action
  addToastMessage(toastMessage: ToastMessage) {
    this.context.commit("addMessage", toastMessage);
  }

  @Action
  removeToastMessage(toastMessage: ToastMessage) {
    this.context.commit("removeMessage", toastMessage);
  }
}
