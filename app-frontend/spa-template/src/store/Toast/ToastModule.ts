import {Action, Module, Mutation, VuexModule} from "vuex-module-decorators";
import {ToastMessage, ToastMessageTypes} from "@/models/ToastMessage";

@Module({
  namespaced: true
})
export default class ToastModule extends VuexModule {
  messages: ToastMessage[] = [];

  @Mutation
  removeMessage(toastMessage: ToastMessage) {
    this.messages = this.messages.filter(message => {
      return message !== toastMessage;
    });
  }

  @Mutation
  addMessage(toastMessage: ToastMessage) {
    this.messages.unshift(toastMessage);
  }

  @Action
  addToastMessage(toastMessage: { title: string; body: string }) {
    this.context.commit("addMessage", {
      title: toastMessage.title,
      body: toastMessage.body,
      date: new Date(),
      duration: ToastMessage.DURATION,
      type: ToastMessageTypes.SUCCESS
    });
  }
}
