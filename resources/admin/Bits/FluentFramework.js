import app from "@/admin/Bits/elements";
import Rest from "@/admin/Bits/Rest";

import {
  applyFilters,
  addFilter,
  addAction,
  doAction,
  removeAllActions,
} from "@wordpress/hooks";

const moment = require("moment");
require("moment/locale/en-gb");
moment.locale("en-gb");

export default class FluentFramework {
  constructor() {
    this.doAction = doAction;
    this.addFilter = addFilter;
    this.addAction = addAction;
    this.applyFilters = applyFilters;
    this.removeAllActions = removeAllActions;
    this.appVars = window.fluentFrameworkAdmin;
    this.app = this.extendVueConstructor();
  }

  extendVueConstructor() {
    const self = this;
    app.provide("$rest", Rest);
    app.provide("$moment", moment);
    app.provide("addFilter", addFilter);
    app.provide("applyFilters", applyFilters);
    app.provide("doAction", doAction);
    app.provide("addAction", addAction);
    app.provide("removeAllActions", removeAllActions);

    return app;
  }

  getExtraComponents() {
    return {
      "ticket-header": {
        template: `<h1>OK</h1>`,
      },
    };
  }

  registerBlock(blockLocation, blockName, block) {
    this.addFilter(blockLocation, this.appVars.slug, function (components) {
      components[blockName] = block;
      return components;
    });
  }

  registerTopMenu(title, route) {
    if (!title || !route.name || !route.path || !route.component) {
      return;
    }

    this.addFilter(
      "fluent_framework_top_menus",
      this.appVars.slug,
      function (menus) {
        menus = menus.filter((m) => m.route !== route.name);
        menus.push({
          route: route.name,
          title: title,
        });
        return menus;
      }
    );

    this.addFilter(
      "fluent_framework_global_routes",
      this.appVars.slug,
      function (routes) {
        routes = routes.filter((r) => r.name !== route.name);
        routes.push(route);
        return routes;
      }
    );
  }
}
