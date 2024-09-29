import React, { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import Form from "../../../component/form/form";
import { FormProps } from "../../../component/form/model";
import Icon from "../../../component/icon/icon";
import Button from "../../../component/button/button";
import { pengguna_form_edit, pengguna_update } from "./controller";
import { mappingForm } from "../../../utils/helper";

class EditData extends Component<RouterInterface> {
  state: Readonly<{
    form: Array<FormProps> | undefined;
    loading: boolean;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      form: undefined,
      loading: false,
    };
    this.callForm = this.callForm.bind(this);
    this.callUpdate = this.callUpdate.bind(this);
  }

  componentDidMount(): void {
    this.callForm();
  }

  callForm() {
    return pengguna_form_edit(this.props.params?.id).then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }
  callUpdate() {
    this.setState({
      loading: true,
    });
    const form = mappingForm(this.state.form);
    return pengguna_update(this.props.params?.id, form)
      .then((res) => {
        this.setState({
          loading: false,
          form: undefined,
        });
        this.callForm();
      })
      .catch((err) => {
        this.setState({
          loading: false,
        });
      });
  }
  render(): ReactNode {
    return (
      <div>
        <div className="mb-10 flex justify-start items-center gap-4">
          <div className="">
            <Icon
              icon="arrow_left"
              className=" cursor-pointer"
              onClick={() => {
                this.props.navigate(-1);
              }}
              color="#dc2626"
              width={30}
              height={30}
            />
          </div>
          <div className="block">
            <h1 className="font-interbold md:text-xl">Edit Pengguna</h1>
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
          title="Ubah Data"
          className="mt-6"
          theme="primary"
          width="block"
          size="small"
          isLoading={this.state.loading}
          onClick={() => {
            this.callUpdate();
          }}
        />
      </div>
    );
  }
}

export default withRouterInterface(EditData);
