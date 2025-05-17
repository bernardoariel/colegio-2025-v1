"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.AfipModule = void 0;
const common_1 = require("@nestjs/common");
const afip_service_1 = require("./afip/afip.service");
const afip_controller_1 = require("./afip/afip.controller");
const pdf_service_1 = require("./pdf/pdf.service");
let AfipModule = class AfipModule {
};
exports.AfipModule = AfipModule;
exports.AfipModule = AfipModule = __decorate([
    (0, common_1.Module)({
        providers: [afip_service_1.AfipService, pdf_service_1.PdfService],
        controllers: [afip_controller_1.AfipController]
    })
], AfipModule);
//# sourceMappingURL=afip.module.js.map