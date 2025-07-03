import { PartialType } from '@nestjs/mapped-types';
import { CreateEscribanoDto } from './create-escribano.dto';

export class UpdateEscribanoDto extends PartialType(CreateEscribanoDto) {}
