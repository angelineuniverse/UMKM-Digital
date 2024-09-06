import { Component } from "react";
import { Outlet } from "react-router-dom";
import { RouterInterface, withRouterInterface } from "../../router/interface";
import { menu } from "./controller";
import Skeleton from "../../component/skeleton/skeleton";
import Icon from "../../component/icon/icon";

class Dashboard extends Component<RouterInterface> {
  state: Readonly<{
    listMenu: Array<any>;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = { listMenu: [] };
    this.fetchMenu = this.fetchMenu.bind(this);
  }

  componentDidMount() {
    this.fetchMenu();
  }

  fetchMenu() {
    menu().then((res) => this.setState({ listMenu: res.data }));
  }
  render() {
    return (
      <div className="w-full flex h-screen overflow-y-hidden">
        <div className="border-r border-gray-300 sm:w-2/12 overflow-y-auto px-3 pt-5">
          <div className="text-lg flex gap-x-3 items-center mb-8">
            <div className="h-8 w-8 bg-gray-600 rounded-full"></div>
            <p className=" font-interbold text-lg">UMKM DIGITAL</p>
          </div>
          <div className="cursor-pointer mb-10 flex gap-x-3">
            <div className="h-12 w-12 bg-gray-400 rounded-full"></div>
            <div className="block">
              <p className="font-intermedium">Nama Pengguna</p>
              <span className="font-intermedium text-xs">Super Admin</span>
            </div>
          </div>
          {this.state.listMenu.length < 1 && (
            <div className="">
              <Skeleton type="custom" className="w-8/12 h-5 mb-2" />
              <Skeleton type="custom" className="w-5/12 h-5 mb-2" />
              <Skeleton type="custom" className="w-12/12 h-5 mb-2" />
              <Skeleton type="custom" className="w-6/12 h-5" />
            </div>
          )}
          {this.state.listMenu?.map((res) => (
            <div key={res?.id}>
              <div
                aria-hidden="true"
                className="flex justify-start mt-2 cursor-pointer w-full items-center"
                onClick={() => {
                  if (res?.children.length > 0) {
                    this.setState((prevState: any) => {
                      return {
                        ...prevState,
                        listMenu: prevState.listMenu.map((menu: any) => {
                          if (menu.id === res.id) {
                            return { ...menu, show: !menu.show };
                          } else {
                            return menu;
                          }
                        }),
                      };
                    });
                  } else {
                    this.props.navigate(res.url);
                  }
                }}
              >
                <p className="text-md mr-auto font-intersemibold">{res.name}</p>
                {res?.show && res.children?.length > 0 && (
                  <Icon
                    icon="arrow_down"
                    className="my-auto"
                    width={13}
                    height={13}
                  />
                )}
                {!res?.show && res.children?.length > 0 && (
                  <Icon
                    icon="arrow_left_simple"
                    className="my-auto"
                    width={20}
                    height={20}
                  />
                )}
              </div>
              {res?.show &&
                res.children?.map((child: any) => (
                  <div
                    aria-hidden="true"
                    key={child.id}
                    className="ml-3 cursor-pointer mt-2"
                    onClick={() => {
                      this.props.navigate(res.url + child.url);
                    }}
                  >
                    <p className="text-md font-intersemibold">{child?.name}</p>
                  </div>
                ))}
            </div>
          ))}
        </div>
        <div className="sm:w-10/12 overflow-y-auto">
          <Outlet></Outlet>
        </div>
      </div>
    );
  }
}

export default withRouterInterface(Dashboard);
