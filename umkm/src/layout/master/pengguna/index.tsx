import { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import Table from "../../../component/table/table";
import { pengguna_index } from "./controller";
import Dialog from "../../../component/dialog/dialog";

class Pengguna extends Component<RouterInterface> {
  state: Readonly<{
    pengguna: any;
    modalShow: boolean;
    detail: undefined;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      pengguna: {
        column: [],
        data: [],
      },
      modalShow: false,
      detail: undefined,
    };
    this.callIndex = this.callIndex.bind(this);
  }

  componentDidMount(): void {
    this.callIndex();
  }

  callIndex(page?: number) {
    pengguna_index(page).then((res) => {
      this.setState({ pengguna: res });
    });
  }

  render(): ReactNode {
    return (
      <div>
        <Table
          column={this.state.pengguna.column}
          title="Management Pengguna"
          useCreate
          useHeadline
          data={this.state.pengguna!.data}
          create={() => {
            this.props.navigate("form");
          }}
          show={(event) => {
            this.setState({
              detail: event,
              modalShow: true,
            });
          }}
        />
        <Dialog
          onOpen={this.state.modalShow}
          onClose={() => {
            this.setState({
              modalShow: false,
            });
          }}
          useHeading
          size="medium"
          title="Detail Pengguna"
          children={
            <>
              <div>Disini Detail</div>
            </>
          }
        />
      </div>
    );
  }
}

export default withRouterInterface(Pengguna);
