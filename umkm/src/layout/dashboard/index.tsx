import { Component } from "react";
import { NavLink, Outlet } from "react-router-dom";
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
              {res.children?.length < 1 && (
                <NavLink to={res.url}>
                  {({ isActive }) => (
                    <div
                      className={`${
                        isActive ? "bg-gray-800 text-white" : ""
                      } px-3 pt-1.5 pb-2 rounded-lg flex items-center gap-x-2 font-intermedium`}
                    >
                      <Icon
                        icon={res.icon}
                        height={20}
                        width={20}
                        color="#7F7F7F"
                      />
                      <p className="md:text-[17px] text-base mr-auto font-intersemibold">
                        {res.name}
                      </p>
                    </div>
                  )}
                </NavLink>
              )}
              {res.children?.length > 0 && (
                <div
                  aria-hidden="true"
                  className="flex gap-x-3 px-3 pt-1.5 pb-2 mt-2 justify-start cursor-pointer w-full items-center"
                  onClick={() => {
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
                  }}
                >
                  <Icon
                    icon={res.icon}
                    height={20}
                    width={20}
                    color="#7F7F7F"
                  />
                  <p className="md:text-[17px] text-base mr-auto font-intersemibold">
                    {res.name}
                  </p>
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
              )}
              {res?.show &&
                res.children?.map((child: any) => (
                  <NavLink to={res.url + child.url}>
                    {({ isActive }) => (
                      <div
                        key={child.id}
                        className={`${
                          isActive ? "bg-gray-800 text-white" : ""
                        } mt-1.5 px-5 pt-1.5 pb-2 rounded-lg flex items-center gap-x-2 font-intermedium`}
                      >
                        <Icon
                          icon={child.icon}
                          height={20}
                          width={20}
                          color="#7F7F7F"
                        />
                        <p className="md:text-[17px] text-base font-intersemibold">
                          {child?.name}
                        </p>
                      </div>
                    )}
                  </NavLink>
                ))}
            </div>
          ))}
        </div>
        <div className="sm:w-10/12 overflow-y-auto p-7">
          <Outlet></Outlet>
        </div>
      </div>
    );
  }
}

export default withRouterInterface(Dashboard);
