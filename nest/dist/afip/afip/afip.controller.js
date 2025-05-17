"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.AfipController = void 0;
const common_1 = require("@nestjs/common");
const afip_service_1 = require("./afip.service");
let AfipController = class AfipController {
    afipService;
    constructor(afipService) {
        this.afipService = afipService;
    }
    async obtenerUltimoComprobante(puntoVenta, tipoComprobante) {
        return this.afipService.obtenerUltimoComprobante(puntoVenta, tipoComprobante);
    }
    async generarPDF(puntoVenta, tipoComprobante, numeroComprobante, res) {
        if (!puntoVenta || !tipoComprobante || !numeroComprobante) {
            throw new common_1.BadRequestException('Todos los parámetros son requeridos');
        }
        try {
            const pdfBuffer = await this.afipService.generarPDF(puntoVenta, tipoComprobante, numeroComprobante);
            res.set({
                'Content-Type': 'application/pdf',
                'Content-Disposition': 'inline; filename=factura.pdf',
                'Content-Length': pdfBuffer.length,
            });
            res.send(pdfBuffer);
        }
        catch (error) {
            console.error('🔴 Error al generar PDF:', error);
            res.status(500).json({ error: 'No se pudo generar el PDF' });
        }
    }
};
exports.AfipController = AfipController;
__decorate([
    (0, common_1.Get)('ultimo-comprobante'),
    __param(0, (0, common_1.Query)('puntoVenta')),
    __param(1, (0, common_1.Query)('tipoComprobante')),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Number]),
    __metadata("design:returntype", Promise)
], AfipController.prototype, "obtenerUltimoComprobante", null);
__decorate([
    (0, common_1.Get)('pdf'),
    __param(0, (0, common_1.Query)('puntoVenta')),
    __param(1, (0, common_1.Query)('tipoComprobante')),
    __param(2, (0, common_1.Query)('numeroComprobante')),
    __param(3, (0, common_1.Res)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Number, Number, Object]),
    __metadata("design:returntype", Promise)
], AfipController.prototype, "generarPDF", null);
exports.AfipController = AfipController = __decorate([
    (0, common_1.Controller)('afip'),
    __metadata("design:paramtypes", [afip_service_1.AfipService])
], AfipController);
//# sourceMappingURL=afip.controller.js.map