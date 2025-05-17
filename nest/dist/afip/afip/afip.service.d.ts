import { PdfService } from '../pdf/pdf.service';
export declare class AfipService {
    private readonly pdfService;
    private afip;
    constructor(pdfService: PdfService);
    obtenerUltimoComprobante(pv: number, tipo: number): Promise<{
        ultimo: any;
    }>;
    generarPDF(puntoVenta: number, tipoComprobante: number, numeroComprobante: number): Promise<Buffer<ArrayBufferLike>>;
}
