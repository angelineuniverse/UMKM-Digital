import { Component } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { FormProps } from "../../../component/form/model";
import Form from "../../../component/form/form";
import { menu_form } from "./controller";
import Button from "../../../component/button/button";

class FormMenu extends Component<RouterInterface> {
  state: Readonly<{
    form: Array<FormProps> | undefined;
    output: any;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      form: undefined,
      output: undefined,
    };

    this.callForm = this.callForm.bind(this);
  }

  componentDidMount(): void {
    this.callForm();
  }
  callForm() {
    menu_form().then((res) => {
      this.setState({ form: res.data });
    });
  }
  render() {
    return (
      <div>
        <div className="mb-8 ">
          <p className="font-interbold text-xl">Buat Menu Baru</p>
          <span className="font-interregular text-sm">
            Pastikan semua data di isi
          </span>
        </div>
        <Form
          lengthLoading={3}
          classNameLoading="grid grid-cols-2 gap-4"
          className="grid grid-cols-2 gap-4"
          form={this.state.form!}
        />
        <Button
          title="Simpan"
          theme="primary"
          size="small"
          className="mt-8"
          width="block"
          isLoading
          onClick={() => {
            console.log(this.state.form);
          }}
        />
      </div>
    );
  }
}

export default withRouterInterface(FormMenu);
