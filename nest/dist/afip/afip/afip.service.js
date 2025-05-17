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
Object.defineProperty(exports, "__esModule", { value: true });
exports.AfipService = void 0;
const common_1 = require("@nestjs/common");
const pdf_service_1 = require("../pdf/pdf.service");
const Afip = require('@afipsdk/afip.js');
let AfipService = class AfipService {
    pdfService;
    afip;
    constructor(pdfService) {
        this.pdfService = pdfService;
        try {
            this.afip = new Afip({ CUIT: 20409378472, production: false });
            console.log('🟢 AFIP inicializado:', this.afip);
        }
        catch (e) {
            console.error('🔴 Error al instanciar Afip:', e);
        }
    }
    async obtenerUltimoComprobante(pv, tipo) {
        console.log('🧪 afip instance:', this.afip);
        console.log('🧪 afip.ElectronicBilling:', this.afip?.ElectronicBilling);
        if (!this.afip?.ElectronicBilling) {
            throw new Error('❌ ElectronicBilling no está definido');
        }
        const result = await this.afip.ElectronicBilling.getLastVoucher(pv, tipo);
        return { ultimo: result };
    }
    async generarPDF(puntoVenta, tipoComprobante, numeroComprobante) {
        try {
            const pdfBuffer = this.pdfService.generarComprobantePDF({
                puntoVenta,
                tipoComprobante,
                numeroComprobante,
            });
            return pdfBuffer;
        }
        catch (error) {
            console.error('Error al generar el PDF:', error);
            throw new Error('No se pudo generar el PDF');
        }
    }
};
exports.AfipService = AfipService;
exports.AfipService = AfipService = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [pdf_service_1.PdfService])
], AfipService);
//# sourceMappingURL=afip.service.js.map