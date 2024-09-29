import { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import Table from "../../../component/table/table";
import { pengguna_index, pengguna_destroy } from "./controller";
import Dialog from "../../../component/dialog/dialog";
import Button from "../../../component/button/button";

class Pengguna extends Component<RouterInterface> {
  state: Readonly<{
    pengguna: any;
    modalDelete: boolean;
    loading: boolean;
    detail: any;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      pengguna: {
        column: [],
        data: [],
      },
      modalDelete: false,
      loading: false,
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

  callDestroy() {
    this.setState({ loading: true });
    pengguna_destroy(this.state.detail?.id)
      .then((res) => {
        this.setState({ modalDelete: false, loading: false });
        this.callIndex();
      })
      .catch((err) => {
        this.setState({ loading: false });
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
            this.props.navigate("edit/" + event.id);
          }}
          delete={(event) => {
            this.setState({
              detail: event,
              modalDelete: true,
            });
          }}
        />
        <Dialog
          onOpen={this.state.modalDelete}
          size="small"
          onClose={() => {
            this.setState({
              modalDelete: false,
            });
          }}
          children={
            <>
              <div className="text-center w-fit">
                <h1 className=" font-interbold mb-3 text-red-700 uppercase text-lg">
                  Hapus Data
                </h1>
                <p className=" font-interregular">
                  Apabila anda menghapus data maka seluruh informasi akan
                  dihapus di system. data yang dihapus tidak bisa dipulihkan
                  kembali
                </p>
                <Button
                  className="mt-5"
                  width="full"
                  size="medium"
                  title="Hapus Data"
                  theme="error"
                  isLoading={this.state.loading}
                  onClick={() => {
                    this.callDestroy();
                  }}
                />
              </div>
            </>
          }
        />
      </div>
    );
  }
}

export default withRouterInterface(Pengguna);
