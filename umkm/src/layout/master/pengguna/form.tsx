import React, { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import Form from "../../../component/form/form";
import { FormProps } from "../../../component/form/model";
import Icon from "../../../component/icon/icon";
import Button from "../../../component/button/button";
import { pengguna_form } from "./controller";

class FormData extends Component<RouterInterface> {
  state: Readonly<{
    form: Array<FormProps> | undefined;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      form: undefined,
    };
    this.callForm = this.callForm.bind(this);
  }

  componentDidMount(): void {
    this.callForm();
  }

  callForm() {
    return pengguna_form().then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }
  render(): ReactNode {
    return (
      <div>
        <div className="mb-10 flex justify-start items-center gap-4">
          <div className="">
            <Icon icon="arrow_left" color="#dc2626" width={30} height={30} />
          </div>
          <div className="block">
            <h1 className="font-interbold md:text-xl">Form Pengguna</h1>
            <p className=" text-sm">
              Pastikan anda melengkapi semua input yang tersedia
            </p>
          </div>
        </div>
        <Form
          form={this.state.form}
          lengthLoading={5}
          className="grid grid-cols-3 gap-4"
          classNameLoading="grid grid-cols-3 gap-4"
        ></Form>
        <Button
          title="Simpan"
          className="mt-6"
          theme="primary"
          width="block"
          size="small"
        />
      </div>
    );
  }
}

export default withRouterInterface(FormData);
