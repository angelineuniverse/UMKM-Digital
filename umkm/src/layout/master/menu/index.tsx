import { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";

class Index extends Component<RouterInterface> {
  state: Readonly<{}>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {};
  }
  render(): ReactNode {
    return (
      <div>
        <p>React Menu</p>
      </div>
    );
  }
}

export default withRouterInterface(Index);
