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
    this.messages.push(toastMessage);
  }

  @Action
  addToastMessage(toastMessage: {
    title: string;
    body: string;
    date?: Date;
    duration?: number;
    type?: string;
  }) {

    this.context.commit("addMessage", {
      title: toastMessage.title,
      body: toastMessage.body,
      date: toastMessage.date || new Date(),
      duration: toastMessage.duration || ToastMessage.DURATION,
      type: toastMessage.type || ToastMessageTypes.SUCCESS
    });
  }

  @Action
  removeToastMessage(toastMessage: ToastMessage) {
    this.context.commit("removeMessage", toastMessage);
  }
}
