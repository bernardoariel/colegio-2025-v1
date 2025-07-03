import { Module } from '@nestjs/common';
import { ApostillasService } from './apostillas.service';
import { ApostillasController } from './apostillas.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Apostilla } from './apostilla.entity';

@Module({
  imports: [TypeOrmModule.forFeature([Apostilla])],
  controllers: [ApostillasController],
  providers: [ApostillasService],
})
export class ApostillasModule {}
