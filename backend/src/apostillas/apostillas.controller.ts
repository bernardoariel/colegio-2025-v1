import { Controller, Get, Post, Body, Patch, Param, Delete, Query } from '@nestjs/common';
import { ApostillasService } from './apostillas.service';
import { CreateApostillaDto } from './dto/create-apostilla.dto';
import { UpdateApostillaDto } from './dto/update-apostilla.dto';

@Controller('apostillas')
export class ApostillasController {
  constructor(private readonly apostillasService: ApostillasService) {}

  @Post()
  create(@Body() createApostillaDto: CreateApostillaDto) {
    return this.apostillasService.create(createApostillaDto);
  }

  @Get()
  findAll(@Query('item') item?: string, @Query('valor') valor?: string) {
    if (item && valor) {
      return this.apostillasService.findByField(item, valor);
    }
    return this.apostillasService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.apostillasService.findOne(+id);
  }

  @Get('/venta/:idventa')
  findAllByVenta(@Param('idventa') idventa: string) {
    return this.apostillasService.findAllByVenta(+idventa);
  }

  @Get('/json')
  findJsonByField(@Query('item') item: string, @Query('valor') valor: string) {
    return this.apostillasService.findJsonByField(item, valor);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateApostillaDto: UpdateApostillaDto) {
    return this.apostillasService.update(+id, updateApostillaDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.apostillasService.remove(+id);
  }
}
