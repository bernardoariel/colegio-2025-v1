import { AfipService } from './afip.service';
import { Response } from 'express';
export declare class AfipController {
    private readonly afipService;
    constructor(afipService: AfipService);
    obtenerUltimoComprobante(puntoVenta: number, tipoComprobante: number): Promise<any>;
    generarPDF(puntoVenta: number, tipoComprobante: number, numeroComprobante: number, res: Response): Promise<void>;
}
